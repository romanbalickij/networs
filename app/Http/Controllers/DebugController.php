<?php

namespace App\Http\Controllers;

use AmrShawky\LaravelCurrency\Facade\Currency;
use App\Enums\BookmarkType;
use App\Enums\ChatType;
use App\Enums\EventType;
use App\Enums\FileType;
use App\Enums\HistoryType;
use App\Enums\InteractionType;
use App\Enums\InvoiceType;
use App\Enums\NotificationType;
use App\Enums\PageType;
use App\Enums\PostType;
use App\Enums\ReactionType;
use App\Enums\TrackFnType;
use App\Enums\UserType;
use App\Events\NewMessageEvent;
use App\Events\RealTimeMessage;
use App\Events\UserOnlineEvent;
use App\Exceptions\PaymentFailedException;
use App\Http\Controllers\Api\UserController;
use App\Http\Requests\User\WithdrawalBalanceRequest;
use App\Http\Resources\Account\AccountInformationResource;
use App\Http\Resources\AccountManager\AccountManagerCollection;
use App\Http\Resources\Admin\Invoice\PlatformInvoiceCollection;
use App\Http\Resources\Attachment\AttachmentCollection;
use App\Http\Resources\Attachment\AttachmentResource;
use App\Http\Resources\Bookmark\BookmarkCollection;
use App\Http\Resources\Bookmark\UserBookmarkResource;
use App\Http\Resources\Campaign\CampaignCollection;
use App\Http\Resources\Campaign\CampaignDetailResource;
use App\Http\Resources\Chat\BroadcastNewMessageResource;
use App\Http\Resources\Chat\ConversationCollection;
use App\Http\Resources\Chat\ConversationResource;
use App\Http\Resources\Chat\MessageCollection;
use App\Http\Resources\Chat\MessageResource;
use App\Http\Resources\Comment\CommentCollection;
use App\Http\Resources\Comment\CommentResource;
use App\Http\Resources\Even\NewNotificationResource;
use App\Http\Resources\Interaction\InteractionCollection;
use App\Http\Resources\Invoice\InvoiceCollection;
use App\Http\Resources\Landing\PageCollection;
use App\Http\Resources\Landing\PageResource;
use App\Http\Resources\Managed\ManagedCollection;
use App\Http\Resources\Notification\Comment\CommentNotificationResource;
use App\Http\Resources\Notification\NotificationCollection;
use App\Http\Resources\Notification\NotificationResource;
use App\Http\Resources\Notification\UserNotificationResource;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostCreatorContentCollection;
use App\Http\Resources\Post\PostCreatorContentResource;
use App\Http\Resources\Post\PostResource;
use App\Http\Resources\Post\PostStatisticCollection;
use App\Http\Resources\Post\PostStatisticResource;
use App\Http\Resources\Post\PostTopCollection;
use App\Http\Resources\Post\TopPostCollection;
use App\Http\Resources\ReferralLink\ReferralInvitedUserCollection;
use App\Http\Resources\ReferralLink\ReferralLinkCollection;
use App\Http\Resources\ReferralLink\ReferralLinkResource;
use App\Http\Resources\Subscriber\SubscriberCollection;
use App\Http\Resources\SubscriberGroup\SubscriberGroupIndexCollection;
use App\Http\Resources\Subscription\SubscriptionCollection;
use App\Http\Resources\Subscription\SubscriptionResource;
use App\Http\Resources\TestCollection;
use App\Http\Resources\TestResource;
use App\Http\Resources\User\BlockingUserCollection;
use App\Http\Resources\User\ContentCreatorResource;
use App\Http\Resources\User\CreatorSuggestionCollection;
use App\Http\Resources\User\LandingCreatorCollection;
use App\Http\Resources\User\ManagerAccountResource;
use App\Http\Resources\User\PlanCollection;
use App\Http\Resources\User\ProfileResource;
use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserInvoiceCollection;
use App\Http\Resources\User\UserPostCollection;
use App\Http\Resources\User\UserResource;
use App\Jobs\ImageBlurJob;
use App\Jobs\QueuedEmail;
use App\Mail\ExampleEmail;
use App\Models\AdCampaign;
use App\Models\Bookmark;
use App\Models\Chat;
use App\Models\Click;
use App\Models\Comment;
use App\Models\Donation;
use App\Models\File;
use App\Models\GatewayPayment;
use App\Models\GatewayPayment as UserPayment;
use App\Models\History;
use App\Models\Interaction;
use App\Models\InterfaceText;
use App\Models\Invoice;
use App\Models\LandingCreator;
use App\Models\Message;
use App\Models\Page;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Plan;
use App\Models\Post;
use App\Models\PostClickthroughHistory;
use App\Models\PostInterestHistory;
use App\Models\PostShowHistory;
use App\Models\Reaction;
use App\Models\ReferralLink;
use App\Models\SubscriberGroup;
use App\Models\Subscription;
use App\Models\User;
use App\Notifications\NotificationHelper;
use App\Notifications\PromotionNotification;
use App\Notifications\RealTimeNotification;
use App\Notifications\SendUnreadNotification;
use App\Notifications\SubscriptionAutoProlongedMailNotification;
use App\Notifications\TestVerifyMailNotification;
use App\Notifications\UnreadMessageMailNotification;
use App\Services\Actions\AttachFileAction;
use App\Services\Actions\BlurImageAction;
use App\Services\Actions\ErrorHandlerAction;
use App\Services\Actions\EventMessageAction;
use App\Services\Actions\GenerateGraphDateAction;
use App\Services\Actions\NewNotificationAction;
use App\Services\Actions\PostStatisticAction;
use App\Services\Actions\PromotionMessage;
use App\Services\Actions\SendMessageAction;
use App\Services\Actions\SeReferralAction;
use App\Services\Actions\Statistics\GraphGenerateAction;
use App\Services\Actions\Statistics\MessageStatisticAction;
use App\Services\Actions\SubscribeAction;
use App\Services\Actions\TrackFnsAction;
use App\Services\Actions\UserCalculateRatingAction;
use App\Services\Chat\ChatService;
use App\Services\Chat\ConversationSupport;
use App\Services\Chat\ConversationUser;
use App\Services\Filters\ChatFilter;
use App\Services\Filters\InvoiceFilter;

use App\Services\Filters\PostFilter;
use App\Services\Filters\SubscriberFilter;
use App\Services\Filters\SubscriptionFilter;
use App\Services\Filters\UserFilter;
use App\Services\Payments\Crypto\Ethereum;
use App\Services\Payments\Crypto\TronCrypto;
use App\Services\Payments\CryptoInterface;
use App\Services\Payments\Gateways\CryptoPayment;
use App\Services\Payments\PaymentGateway;
use App\Services\Payments\PaymentHandler\Entity\DonationEntityPayment;
use App\Services\Payments\PaymentHandler\Entity\MediaEntityPPVPayment;
use App\Services\Payments\PaymentHandler\Entity\SubscribeEntityPayment;
use App\Services\Payments\PaymentHandler\PaymentHandler;
use App\Services\Pipeline\SeReferral;
use App\Services\Reports\Invoices\InvoiceReport;
use App\Services\Reports\Invoices\PlatformInvoiceReport;
use App\Services\Reports\ReportRepository;
use App\Services\Reports\SavePfd;
use App\Services\SetExternalTrackerAction;
use App\Services\UnlockMedia\MediaPPV;
use App\Traits\VatCalculator;


use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Database\Seeders\HistorySeeder;
use DateInterval;
use DatePeriod;
use DateTime;
use DeDmytro\CloudflareImages\Facades\CloudflareApi;
use IEXBase\TronAPI\Tron;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Response;
use Intervention\Image\ImageManager;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Illuminate\Pagination\Cursor;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Event;
use Intervention\Image\Facades\Image;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Pusher\Pusher;

use Spatie\Image\Manipulations;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Support\Facades\Queue;
use Illuminate\Support\ServiceProvider;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;


use Web3\Contract;
use Web3\Eth;
use Web3\Formatters\BigNumberFormatter;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use Web3\Utils;
use Web3\Web3;
use Web3p\EthereumTx\Transaction;


class DebugController extends Controller
{

    use NotificationHelper;
    //https://www.figma.com/file/I2zdZeVZ33At9Pi7bljrKl/theFans-%F0%9F%90%87?node-id=8036%3A37746
//https://wedo-outsourcing.atlassian.net/jira/software/projects/DT/boards/1/backlog?assignee=5d4d51200aaab10c537fae6e
    //https://wedo-outsourcing.atlassian.net/browse/DT-1846
    protected $perPage = 20;

    use VatCalculator;
//     return cc.split('').reduce((str, abb, index) => index >= cc.length - 4 ?  str + abb :  str + '#'  , '')

   public function docs() {

       return view('welcome2');
   }

//
//My subscribers - це люди які підписані на мене і які мені платять
//My subscriptions - це люди, на яких я підписаний, і яким я плачу


    public function testServer() {

       $user = User::first();

        return $this->respondWithSuccess(

            (new UserPostCollection($user
                ->posts()
                ->orderBy('id', 'desc')
                ->with([
                    'reactions',
                    'others.entity.payments',
                    'media.entity.payments',
                    'user',
                    'bookmarks',
                    'allComments',
                    'media.bookmarks',
                    'others.bookmarks'
                ])->cursorPaginate(200)
            )
            )->except('creator')
        );

    }

  public function  testConnect() {


      $url ='https://connect.stripe.com/oauth/authorize?response_type=code&client_id=ca_HtsLyRD00sEE5O5DIYazpjdlISGD1MT0&scope=read_write&state=1';

      return redirect($url);
  }
 // test message
// email raltenwerth@example.com
// pass user@user.com

    public  function orderedCount($text) {

        $new = array_reduce(str_split($text), function ($acc, $item) {

            !isset($acc[$item]) ? $acc[$item] = 1 : $acc[$item]++;
             return $acc;
        }, []);

        $formattedResult = [];
        foreach ($new as $letter => $count) {
            $formattedResult[] = [$letter, $count];
        }

        return $formattedResult;
    }
    public function debug(Request $request,
                          InvoiceFilter $invoiceFilter,
                          SubscriberFilter $subscriberFilter,
                          ChatFilter $chatFilter,
                          PostFilter $postFilter,
                          GraphGenerateAction $graphGenerateAction,
                          UserFilter $userFilter,
                          SubscriptionFilter $subscriptionFilter


    ) {



  //     dd($this->getLengthOfMissingArray([[6,3,26],[6,21,5,29],[33,45,46,16,33,13,36,15],[17,46,38,47,14,37,19,50,40],[49],[8,13],[23,18,38,43,18,7,26],[11,25,48,43,27],[]]));
//https://github.com/DarkGhostHunter/Laraload
//https://github.com/Laragear/Preload
//https://stitcher.io/blog/preloading-in-php-74
//https://curatedphp.com/r/effortlessly-create-a-darkghosthunterlaraload/index.html
//https://github.com/Laragear/Preload
//@174.138.3.18
      //  auth()->setUser(User::find(1));
        //57d65d52-3c12-4f95-833e-a11d1783835f

//
//        "private_key" => "4c41bda70f39da0c0d53de448651d941b80c483564e842a7fe264789755d01a1"
//  "public_key" => "04d572d40b65a5f7ea3244ebd62c6e82e25bd81ca591d2c030db068d9531667a604d9790c6add7aa00a7a57e2d0ea019aabb2e61202b193b82d9128de801ec50ec"
//  "address_hex" => "41ebc6550d2ce241bc2736d4ad6355fc1ca540dad9"
//  "address_base58" => "TXTsPWCVfs6uYz5TUsW7DTN6t2JPuYa7v5"
//https://www.trongrid.io/dashboard/keys
//https://github.com/iexbase/tron-api/tree/master/examples
//https://linux-packages.com/ubuntu-focal-fossa/package/php80-gmp
      //  dd($hashed_random_password = Str::random(8));
//https://developers.tron.network/reference/triggersmartcontract


        //https://ethereumnodes.com/
//
//
//        https://github.com/web3-php/web3
//
//        https://github.com/web3p/web3.php
//
//        composer require web3-php/web3 dev-master --ignore-platform-reqs
//
//https://github.com/benrobot/web3.php_send_tokens_example/blob/main/public/sendTokens.php
//https://github.com/web3p/web3.php/issues/168












        auth()->setUser(User::find(1));

        $invoice = Invoice::first();
        $url = createTemporaryLink('notification.download.invoice',['token' => Crypt::encrypt($invoice->id) ]);


        return $url;
        dd($url);


        $chat = Chat::find(3);


        $chat->load([
            'messages.media.entity.payments',
            'messages.others.entity.payments',
            'messages.media.bookmarks',
            'messages.others.bookmarks',
            'messages.reactions',
            'messages.bookmarks',
            'messages.payments',
            'messages' => fn($q) => $q->orderByNew()
        ]);

        return $this->respondWithSuccess(

            new MessageCollection($chat->messages)
        );


        return $this->respondWithSuccess(

            (new NewNotificationResource(\App\Models\Notification::first()))->resolve()
        );


        return $this->respondWithSuccess(

            new NotificationCollection($notification)
        );






    $m = Message::with([
        'media.entity',
        'others.entity',
        'reactions',
        'bookmarks',
        'payments'
    ])->find(9);


        return $this->respondWithSuccess(

            new MessageResource($m)
        );

        $mesages = Message::find([9, 10]);

        $files = [204, 205];


        dd(2);


//https://github.com/DeDmytro/laravel-cloudflare-images#configuration
//https://api.cloudflare.com/#stream-videos-retrieve-video-details



        //    dd(1);

        $url = "https://storage.googleapis.com/zaid-test/Watermarks%20Demo/cf-ad-original.mp4";

        $client = new \GuzzleHttp\Client();

        $cloudflareAccount   = config('services.cloudflare.account');
        $cloudflareEmail     = config('services.cloudflare.email');
        $cloudflareAuthToken = config('services.cloudflare.auth_token');


            DB::beginTransaction();

        $post = array(
            "url" => 'https://storage.googleapis.com/zaid-test/Watermarks%20Demo/cf-ad-original.mp4',
            'meta' => [
                'name' => 'My First Stream Video'
            ]
        );

        $url = "https://api.cloudflare.com/client/v4/accounts/2/stream";

        $data = json_encode($post);



            $response = $client->request('POST', "https://api.cloudflare.com/client/v4/accounts/$cloudflareAccount/stream/copy", [
                'headers' => [
                    'X-Auth-Email' => "{$cloudflareEmail}",
                    'X-Auth-Key' => "{$cloudflareAuthToken}",
                   "Content-Type" => "application/json"
                ],


                'body' => $data,


            ]);

            dd($response);
//        dd(2);

        $info = pathinfo($url);


        $contents2 = file_get_contents($url);
        $file = '/tmp/' . $info['filename'];
        file_put_contents($file, $contents2);

        $response = CloudflareApi::images()->upload($file);

        $image = $response->result;

        dd($image);

        foreach($response->result as $image){

            dd($image->variants->public);
            $image->filename;
            $image->filename;
            $image->variants->thumbnail; //Depends on your Cloudflare Images Variants setting
            $image->variants->original; //Depends on your Cloudflare Images Variants setting
        }

        dd($response);





//        PAY CRYPTO




        $ad2 = 'https://rpc.ankr.com/eth_goerli';
        $ribeccy = 'https://rpc.ankr.com/eth_rinkeby';
        $eth_ropsten = 'https://rpc.ankr.com/eth_ropsten';

        $web3 = new Web3($ribeccy);
        $eth = $web3->eth;
        $eth2 = new Eth($ribeccy);

        $files = public_path('Erc777TokenAbiArray.json');
        $abi = file_get_contents($files);


        $contract = new Contract($web3->provider, $abi);
        $fromAccount = '0xde2bC1d884F9e91F7C8961BB1B6B6635576ae634';
        $toAccount = '0x349EdB8A554d8CC95A62BcdD1E6C08d0390428cA';
        $contractAddress= '0x01BE23585060835E02B77ef475b0Cc51aA1e0709';
        $ethc = $contract->eth;






        dd(2);

//        try{
//            $v = new CryptoPayment(new Ethereum($ribeccy));
//
//           // $v = new CryptoPayment(new TronCrypto('https://nile.trongrid.io'));
//
//            $dollar =intval(1) * (10 ** 6);
//          //  dd($dollar , intval($dollar) * (10 ** 6));
//
//        // ***************************Ethereum********************************
//        //   $balance = $v->contractBalance('0xde2bC1d884F9e91F7C8961BB1B6B6635576ae634');
//        //   $transaction =  $v->createTransaction('0x349EdB8A554d8CC95A62BcdD1E6C08d0390428cA', $dollar);
//            $tr = $v->getBlockTransaction('0xce9938619d0a3d593b0dab55d13e67c848c86ed213ef8aa025d2bee6db44ad38');
//
//            dd($tr);
//          //  $format =  BigNumberFormatter::format($tr->blockNumber)->toString();;
//
//        }catch (PaymentFailedException $exception) {
//            dd($exception->getMessage());
//        }







      //  dd(2);
        $v = new CryptoPayment(new TronCrypto('https://nile.trongrid.io'));

          //  $balance = $v->contractBalance('TEfn9qhYwCeduwsxNjSK7M3jkvmFbyUyjq');

      // $transaction2 = $v->createTransaction('TQCPy89NEFLY6LTQXbEXmfvnHZvgDGM73n',2);

      //  $info = $v->getBlockTransaction('ea69752951ca1da62a5942d2363ea9f6aef3e779527d92cd2550a47ebed92cac');
      //  dd($info);


         dd($v->getBlock());
        //dd($v->createTransaction('TEfn9qhYwCeduwsxNjSK7M3jkvmFbyUyjq',1900));












dd('done');




        echo '<pre>';
        var_dump(opcache_get_status());
        echo '</pre>';

        //  dd(opcache_get_status());
        $user = user()
            ->load('accountManagers.owner');

        return $this->respondWithSuccess(

            (new ProfileResource($user))->except('verified')
        );


        $sub = Subscription::find(75047);

        dd($sub->owner->chat);



     //   $file = File::find(352720);
     //   $file = File::find(352721);
        $file = File::find(352723);
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="'. $file->name .'"',
        ];

        try{
            return Response::make(Storage::disk(config('app.use_file_disk'))->get($file->url), 200, $headers);

        }catch (\Exception $exception) {

            return $this->respondError($exception->getMessage());
        }


















        $string = '<span class="c0 c9">GENERAL TERMS &amp; CONDITIONS</span></p><p class="c13 c18"><span class="c0 c9"></span></p><p class="c16"><span class="c1">PLEASE READ THESE GENERAL TERMS &amp; CONDITIONS CAREFULLY AS THEY CONTAIN IMPORTANT INFORMATION AND AFFECT YOUR LEGAL RIGHTS. </span></p><p class="c16 c36 c18"><span class="c1"></span></p><p class="c16"><span class="c2">LAST UPDATED:</span><span class="c21 c22 c25">[date]</span></p><p class="c16 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_2-0 start" start="1"><li class="c16 c28 li-bullet-0"><span class="c1">These General Terms &amp; Conditions and the agreements incorporated herein by reference, constitute a binding agreement between you (</span><span class="c15 c2 c12">as defined below</span><span class="c1">) and </span><span class="c2">The Fans</span><span class="c1">&nbsp;(</span><span class="c15 c2 c12">as defined below</span><span class="c1">) (the &quot;</span><span class="c0 c9">Terms</span><span class="c1">&quot;) and apply to, and governs your access to, and use of, the </span><span class="c2">Platform</span><span class="c1">&nbsp;(</span><span class="c15 c2 c12">as defined below</span><span class="c1">) and Services (</span><span class="c15 c2 c12">as defined below</span><span class="c1">), unless we have executed a separate written agreement with you for that purpose. We are only willing to make the </span><span class="c2">Platform</span><span class="c1">&nbsp;and Services available to you if you accept all of the Terms. BY CLICKING &quot;TO ACCEPT&quot; OR ACCESSING OR USING, OR BOTH, OUR </span><span class="c2">PLATFORM</span><span class="c1">&nbsp;OR SERVICES, OR BOTH, YOU AGREE TO BE BOUND BY, AND ACCEPT THE TERMS. IF YOU DO NOT AGREE TO THE TERMS, YOU SHALL NOT ACCESS AND USE THE </span><span class="c2">PLATFORM</span><span class="c1">&nbsp;AND SERVICES. If you are accepting the Terms on behalf of a company or other legal entity (the &quot;</span><span class="c0 c9">Entity</span><span class="c1">&quot;), you represent that you have the legal authority to accept the Terms on the Entity&#39;s behalf, in which case &quot;you&quot; will mean that Entity. If you do not have such authority, or if you do not accept the Terms, then we are unwilling to make the </span><span class="c2">Platform</span><span class="c1">&nbsp;and Services available to you. By accessing or using, or both, the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, you affirm that you are of legal age to accept the Terms. &nbsp;</span></li></ol><p class="c16 c18 c37"><span class="c1"></span></p><ol class="c8 lst-kix_list_2-0" start="2"><li class="c16 c28 li-bullet-0"><span class="c2">The Fans</span><span class="c1">&nbsp;may make changes to the Terms from time to time. The changes will be effective immediately upon their publication. Please, review the Terms on a regular basis. You understand and agree that your express acceptance of the Terms or your access to, or use of, or both, the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, after the date of publication of the relevant changes shall constitute your agreement to the updated Terms. You can determine when the Terms </span><span class="c2">were</span><span class="c1">&nbsp;last revised by referring to the &quot;LAST UPDATED&quot; legend at the top </span><span class="c2">of the then-current</span><span class="c1">&nbsp;version of the Terms on the Website (</span><span class="c15 c2 c12">as defined below</span><span class="c1">). </span></li></ol><p class="c16 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_2-0" start="3"><li class="c16 c28 li-bullet-0"><span class="c1">If you are accessing or using, or both, the </span><span class="c2">Platform</span><span class="c1">&nbsp;with third party products, hardware, software applications, programs or devices (the &quot;</span><span class="c0 c9">Third Party Technology</span><span class="c1">&quot;), you agree and acknowledge that: (i) you may be required to enter into a separate license agreement with the relevant third party owner or licensor for the use of such Third Party Technology; (ii) the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, may not be accessible through the Third Party Technology, and (iii) </span><span class="c2">The Fans</span><span class="c1">&nbsp;cannot guarantee that the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, shall always be available on or in connection with such Third Party Technology.</span></li></ol><p class="c16 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_2-0" start="4"><li class="c16 c28 li-bullet-0"><span class="c1">You shall not access and use the </span><span class="c2">Platform</span><span class="c1">&nbsp;and Services if you: (i) do not agree to the Terms, or (ii) are prohibited from accessing or using, or both, the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, by applicable law. You must be 18 (eighteen) years or older (</span><span class="c15 c2 c12">if so required by applicable law</span><span class="c1">) to access and use the </span><span class="c2">Platform</span><span class="c1">&nbsp;and Services. If you are under 18 (eighteen) years of age </span><span class="c2">or older (</span><span class="c2 c22">if so required by applicable law</span><span class="c2">)</span><span class="c1">, you are not permitted to access and use the </span><span class="c2">Platform</span><span class="c1">&nbsp;and Services (the </span><span class="c2">&quot;</span><span class="c21">Age Restriction</span><span class="c2">&quot;</span><span class="c1">).</span></li></ol><p class="c16 c11 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_2-0" start="5"><li class="c16 c28 li-bullet-0"><span class="c1">To access or use, or both, the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, you may be asked to provide certain registration details or other information. It is a condition of your access to, or use of, or both, the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, that all such information will be correct, current, and complete. </span></li></ol><p class="c16 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_2-0" start="6"><li class="c16 c28 li-bullet-0"><span class="c1">The Terms or any part thereof may be translated into other languages for your convenience. The English language version of each of these documents is the version that </span><span class="c2">prevails</span><span class="c1">&nbsp;at all times and in the event of any conflict between the English language version and a translated version, the English language version shall prevail.</span></li></ol><p class="c16 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_2-0" start="7"><li class="c16 c28 li-bullet-0"><span class="c1">Certain features of the </span><span class="c2">Platform</span><span class="c1">&nbsp;may be offered while still </span><span class="c2">in the</span><span class="c1">&nbsp;&quot;beta&quot; version (the &quot;</span><span class="c0 c9">Beta Versions</span><span class="c1">&quot;). </span><span class="c2">The Fans</span><span class="c1">&nbsp;shall use its reasonable efforts to identify the Beta Versions by marking them within the </span><span class="c2">Platform</span><span class="c1">. By accepting the Terms, you understand and acknowledge that the Beta Versions are being provided as a &quot;BETA&quot; version and made available on an &quot;AS IS&quot; or &quot;AS AVAILABLE&quot; basis. The Beta Versions may contain bugs, errors, and other problems. YOU ASSUME ALL RISKS AND ALL COSTS ASSOCIATED WITH YOUR USE OF THE BETA VERSIONS, INCLUDING ANY INTERNET ACCESS FEES, BACK-UP EXPENSES, COSTS INCURRED FOR THE USE OF YOUR DEVICE AND PERIPHERALS, AND ANY DAMAGE TO ANY EQUIPMENT, SOFTWARE, INFORMATION OR DATA. In addition, we shall not be obliged to provide any maintenance, technical, or other support for the Beta Versions.</span></li></ol><p class="c16 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_2-0" start="8"><li class="c16 c28 li-bullet-0"><span class="c1">Please,</span><span class="c9 c2 c19">&nbsp;refer to our&nbsp;Privacy Policy at: </span><span class="c21 c15 c25">[link to Privacy Policy]</span><span class="c9 c2 c19">&nbsp;for information about how we collect, use, and share personal information about you. The Privacy Policy is hereby incorporated by this reference into the Terms. You agree to the collection, use, storage, and disclosure of your personal information in accordance with our Privacy Policy.</span></li></ol><p class="c16 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-0 start" start="1"><li class="c14 li-bullet-1"><span class="c0 c9">DEFINITIONS AND INTERPRETATIONS </span></li></ol><p class="c7 c24"><span class="c4 c0"></span></p><ol class="c8 lst-kix_list_8-1 start" start="1"><li class="c6 li-bullet-2"><span class="c1 c5">In addition to the terms defined elsewhere in the Terms, for all purposes of the Terms, the following terms have the meanings set forth in this section 1.1:</span></li></ol><p class="c7 c24"><span class="c1 c5"></span></p><ol class="c8 lst-kix_list_7-1 start" start="1"><li class="c6 li-bullet-3"><span class="c1 c5">&quot;</span><span class="c4 c0">Affiliate</span><span class="c1 c5">&quot; means, in relation to any person at a given time, any other person that, directly or indirectly, or both, controls, is controlled by or is under common control, with such person. For the purposes of the Terms, &quot;</span><span class="c4 c0">control</span><span class="c1 c5">&quot; (</span><span class="c15 c2 c5 c12">including, with correlative meanings, the terms </span><span class="c0 c15 c5">&quot;controlled by</span><span class="c15 c2 c5 c12">&quot; and &quot;</span><span class="c0 c15 c5">under common control with</span><span class="c15 c2 c5 c12">&quot;</span><span class="c1 c5">), as used with respect to any person, means the possession, directly or indirectly, or both, of the power to direct or cause the direction, or both, of the management and policies of such person, whether through the ownership of voting shares, by contract, or otherwise;</span></li></ol><p class="c7"><span class="c1 c5"></span></p><ol class="c8 lst-kix_list_7-1" start="2"><li class="c6 li-bullet-3"><span class="c2 c5">&quot;</span><span class="c21 c5">Content Maker</span><span class="c2 c5">&quot; means any person who registered the special account with The Fans substantially for the purposes of making available its User Content (</span><span class="c2 c5 c22">as defined below</span><span class="c1 c5">) to the other Users;</span></li></ol><p class="c7 c11"><span class="c1 c5"></span></p><ol class="c8 lst-kix_list_7-1" start="3"><li class="c6 li-bullet-3"><span class="c1 c5">&quot;</span><span class="c21 c5">Platform</span><span class="c1 c5">&quot; means any one or more of the following: the website of </span><span class="c2 c5">The Fans</span><span class="c1 c5">, available at:</span><span class="c2 c5">&nbsp;</span><span class="c5 c12 c34">thefans.com</span><span class="c1 c5">&nbsp;</span><span class="c1 c5">(the &quot;</span><span class="c4 c0">Website</span><span class="c1 c5">&quot;), our application programming interface and any other correlative software, tools, features, or functionalities provided on or in connection with the Services; &nbsp;</span></li></ol><p class="c16 c11 c18"><span class="c1 c5"></span></p><ol class="c8 lst-kix_list_7-1" start="4"><li class="c6 li-bullet-4"><span class="c1 c5">&quot;</span><span class="c4 c0">IP Rights</span><span class="c1 c5">&quot; means all vested, contingent and future intellectual property rights, including worldwide statutory and common law rights, relating to, or owned by the relevant person anywhere in the world in IP, and all its variations, modifications or enhancements together with any application or right to apply for registration, renewal, extension or protection of those rights;</span></li></ol><p class="c7 c11"><span class="c1"></span></p><ol class="c8 lst-kix_list_7-1" start="5"><li class="c6 li-bullet-2"><span class="c1">&quot;</span><span class="c0 c9">IP</span><span class="c1">&quot;</span><span class="c0 c9">&nbsp;</span><span class="c1">means: any or all of the following anywhere in the world: (i) all patents; (ii) all inventions (</span><span class="c15 c2 c12">whether patentable or not</span><span class="c1">), ideas, processes, invention disclosures, improvements, trade secrets, proprietary information, know-how, technology, improvements, discoveries, technical data, customer lists, proprietary processes and formulae, all source and object code, algorithms, architectures, structures, display screens, layouts, development tools and all documentation and media constituting, describing or relating to the above, including manuals, memoranda and records; (iii) all copyrights, copyrightable material including derivative works, revisions, transformations and adaptations, material that is subject to non-copyright disclosure protections, and all other works of authorship and designs (</span><span class="c15 c2 c12">whether or not </span><span class="c15 c2 c5 c12">copyrightable</span><span class="c1 c5">); (iv) all Trademarks; (v) domain names; (vi) websites and related content, and (vii) all manuals, documentation and materials relating to the above;</span></li></ol><p class="c7"><span class="c4 c0"></span></p><ol class="c8 lst-kix_list_7-1" start="6"><li class="c6 li-bullet-4"><span class="c2 c5">&quot;</span><span class="c21 c5">The Fans</span><span class="c4 c0">&quot; </span><span class="c1 c5">(</span><span class="c1 c5">&quot;</span><span class="c4 c0">we</span><span class="c1 c5">&quot;, &quot;</span><span class="c4 c0">us</span><span class="c1 c5">&quot; or &quot;</span><span class="c4 c0">our</span><span class="c1 c5">&quot;</span><span class="c1 c5">)</span><span class="c4 c0">&nbsp;</span><span class="c2 c5">THE FANS RESEARCH LABS LTD, a legal entity incorporated under the laws of England and Wales, having its registered address at 41 Devonshire Street, Ground Floor, London, United Kingdom, W1G 7AJ, with company number: 14106924</span><span class="c1 c5">;</span></li></ol><p class="c27"><span class="c1 c5">&nbsp;</span></p><ol class="c8 lst-kix_list_7-1" start="7"><li class="c6 li-bullet-2"><span class="c1 c5">&quot;</span><span class="c4 c0">Services</span><span class="c1 c5">&quot; means all of the services available on or through the </span><span class="c2 c5">Platform;</span></li></ol><p class="c7"><span class="c1 c5"></span></p><ol class="c8 lst-kix_list_7-1" start="8"><li class="c6 li-bullet-4"><span class="c1 c5">&quot;</span><span class="c4 c0">Trademarks</span><span class="c1 c5">&quot; means: (i) the trademarks, trade names and service marks used by the relevant person, whether registered or unregistered; (ii) the respective stylistic marks and distinctive logotypes for such trademarks, trade names and service marks, and (iii) such other marks and logotypes as the relevant person may designate from time to time in writing: a</span><span class="c1">nd </span></li></ol><p class="c7 c24"><span class="c1"></span></p><ol class="c8 lst-kix_list_7-1" start="9"><li class="c6 li-bullet-2"><span class="c1">&quot;</span><span class="c0 c9">User</span><span class="c1">&quot; (&quot;</span><span class="c0 c9">you</span><span class="c1">&quot;, or &quot;</span><span class="c0 c9">your</span><span class="c1">&quot;) means you as the user of the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both</span><span class="c2">, regardless of whether you are the Content Maker or not</span><span class="c1">.</span></li></ol><p class="c16 c11 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="2"><li class="c6 li-bullet-3"><span class="c1">&quot;</span><span class="c2">H</span><span class="c1">ereof&quot;, &quot;herein&quot;, &quot;hereunder&quot;, &quot;hereby&quot; and words of similar import will, unless otherwise stated, be construed to refer to the Terms as a whole and not to any particular provision of the Terms</span><span class="c2">.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="3"><li class="c6 li-bullet-2"><span class="c1">&quot;</span><span class="c2">I</span><span class="c1">nclude(s)&quot; and &quot;including&quot; shall be construed to be followed by the words &quot;without limitation&quot;</span><span class="c2">.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="4"><li class="c6 li-bullet-3"><span class="c1">&quot;</span><span class="c2">O</span><span class="c1">r&quot; shall be construed to be the &quot;inclusive or&quot; rather than &quot;exclusive or&quot; unless the context requires otherwise</span><span class="c2">.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="5"><li class="c6 li-bullet-4"><span class="c2">A</span><span class="c1">ny rule of construction to the effect that ambiguities are to be resolved against the drafting party shall not be applied in the construction or interpretation of the Terms</span><span class="c2">.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="6"><li class="c6 li-bullet-2"><span class="c2">S</span><span class="c1">ection titles, captions and headings are for convenience of reference only and have no legal or contractual effect</span><span class="c2">.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="7"><li class="c6 li-bullet-3"><span class="c2">W</span><span class="c1">henever the context requires: the singular number shall include the plural, and </span><span class="c15 c2 c12">vice versa</span><span class="c1">; the masculine gender shall include the feminine and neuter genders; the feminine gender shall include the masculine and neuter genders; and the neuter gender shall include the masculine and feminine genders.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-0" start="2"><li class="c14 li-bullet-1"><span class="c21">ACCESS AND </span><span class="c0">USE THE </span><span class="c21">PLATFORM</span><span class="c0 c9">&nbsp;AND SERVICES. GENERAL PROVISIONS</span></li></ol><p class="c16 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="8"><li class="c6 li-bullet-2"><span class="c1">In consideration for your acceptance of the Terms and your payment of </span><span class="c2">The Fans</span><span class="c1">&nbsp;Fees (</span><span class="c15 c2 c12">as defined below</span><span class="c1">) (</span><span class="c15 c2 c12">if applicable</span><span class="c1">) </span><span class="c2">The Fans</span><span class="c1">&nbsp;grants you the limited, revocable, personal, non-transferable, non-sublicensable and non-exclusive right to access </span><span class="c2">and</span><span class="c1">&nbsp;use</span><span class="c2">&nbsp;</span><span class="c1">the </span><span class="c2">Platform</span><span class="c1">&nbsp;for the purposes of utilizing the Services.</span></li></ol><p class="c16 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="9"><li class="c6 li-bullet-4"><span class="c1">You shall not access or use, or both, the </span><span class="c2">Platform of Services, or both,</span><span class="c1">&nbsp;in any manner that may impair, overburden, damage, disable or otherwise compromise the </span><span class="c2">Platform</span><span class="c1">.</span></li></ol><p class="c16 c11 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="10"><li class="c6 li-bullet-2"><span class="c1">When you access or use, or both, the </span><span class="c2">Platform</span><span class="c1">, you agree and undertake to comply with the following provisions:</span></li></ol><p class="c16 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_6-1 start" start="1"><li class="c6 li-bullet-2"><span class="c1">during the access to, or use of, or both, </span><span class="c2">the Platform</span><span class="c1">&nbsp;or Services, of both, all activities you carry out should comply with the requirements of applicable laws and regulations, the Terms, and various guidelines of </span><span class="c2">The Fans</span><span class="c1">;</span></li></ol><p class="c7 c24"><span class="c1"></span></p><ol class="c8 lst-kix_list_6-1" start="2"><li class="c6 li-bullet-3"><span class="c1">your use of the </span><span class="c2">Platform or Services, or both,</span><span class="c1">&nbsp;should not violate public interests, public morals, or the legitimate interests of others; </span></li></ol><p class="c7 c11"><span class="c1"></span></p><ol class="c8 lst-kix_list_6-1" start="3"><li class="c6 li-bullet-4"><span class="c1">you shall comply with the Age Restriction; </span></li></ol><p class="c7 c11"><span class="c1"></span></p><ol class="c8 lst-kix_list_6-1" start="4"><li class="c6 li-bullet-3"><span class="c1">you shall not be prohibited from accessing to, or using of, or both, the Platform or Services, or both, receiving under applicable laws;</span></li></ol><p class="c7 c11"><span class="c1"></span></p><ol class="c8 lst-kix_list_6-1" start="5"><li class="c6 li-bullet-4"><span class="c2">you shall not have previously disabled your Account (</span><span class="c2 c22">as defined below</span><span class="c1">) for violation of law or any of our policies; </span></li></ol><p class="c7 c11"><span class="c1"></span></p><ol class="c8 lst-kix_list_6-1" start="6"><li class="c6 li-bullet-2"><span class="c1">you shall provide us with the truthful, accurate, up-to-date and complete in all aspects information, which may be requested by us from time to time; and</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_6-1" start="7"><li class="c6 li-bullet-4"><span class="c1">without prior written consent from </span><span class="c2">The Fans</span><span class="c1">, you shall not: (i)</span><span class="c9 c2 c17">&nbsp;copy, modify, reproduce, translate, localize, port </span><span class="c1">or</span><span class="c9 c2 c17">&nbsp;otherwise create derivatives of any part of the </span><span class="c2 c17">Platform</span><span class="c9 c2 c17">&nbsp;or Services, or both; (ii) reverse engineer, disassemble, decompile or otherwise attempt to discover the source code or structure, sequence and organization of all or any part of the </span><span class="c2 c17">Platform or Services, or both</span><span class="c9 c2 c17">&nbsp;(</span><span class="c15 c2 c17">except that this restriction shall not apply to the limited extent restrictions on reverse engineering are prohibited by applicable local, state, provincial, national or other law, rule or regulation</span><span class="c9 c2 c17">); (iii) rent, lease, resell, distribute, use in any unauthorized or unintended manner</span><span class="c1">&nbsp;</span><span class="c9 c2 c17">or otherwise exploit the </span><span class="c2 c17">Platform</span><span class="c9 c2 c17">&nbsp;or Services, or both, for purposes not contemplated by the Terms; (iv) remove or alter any proprietary notices, Trademarks or labels on or in the </span><span class="c2 c17">Platform</span><span class="c9 c2 c17">&nbsp;or Services, or both; and (v) engage in any activity that interferes with or disrupts the </span><span class="c2 c17">Platform</span><span class="c9 c2 c17">&nbsp;or Services, or both. </span></li></ol><p class="c16 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="11"><li class="c6 li-bullet-3"><span class="c1">Your access</span><span class="c2">&nbsp;to,</span><span class="c1">&nbsp;</span><span class="c2">or</span><span class="c1">&nbsp;use of, the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, may be interrupted from time to time for any of several reasons, including the malfunction of equipment, periodic updating, maintenance, or repair of the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, or other actions that </span><span class="c2">The Fans</span><span class="c1">, in its sole discretion, may elect to take.</span></li></ol><p class="c16 c11 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="12"><li class="c6 li-bullet-3"><span class="c1">Because we have a growing number of services and enhancing the </span><span class="c2">Platform</span><span class="c1">&nbsp;and Services to better meet the needs of our users&#39; community, we sometimes need to provide additional terms for specific services (</span><span class="c15 c2 c12">and such services are deemed part of the &quot;Services</span><span class="c2 c22">&#39;&#39; hereunder</span><span class="c15 c2 c12">&nbsp;and shall also be subject to the Terms</span><span class="c1">). Those additional terms and conditions, which are available with the relevant service, then become part of your agreement with us if you use those services. In the event of a conflict between the Terms and any additional applicable terms we may provide for a specific service, such additional terms shall control for that specific service. We also may from time to </span><span class="c2">time modify</span><span class="c1">, suspend or discontinue, temporarily or permanently, any part of the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, for any reason. </span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="13"><li class="c6 li-bullet-3"><span class="c1 c5">Any rights not expressly granted in the Terms are reserved by </span><span class="c2 c5">The Fans</span><span class="c1 c5">.</span></li></ol><p class="c16 c11 c18"><span class="c0 c4"></span></p><ol class="c8 lst-kix_list_8-0" start="3"><li class="c14 li-bullet-1"><span class="c0 c5">USE OF THE </span><span class="c21 c5">PLATFORM</span><span class="c4 c0">&nbsp;AND SERVICES. ACCOUNT AND SECURITY</span></li></ol><p class="c7 c26"><span class="c4 c0"></span></p><ol class="c8 lst-kix_list_8-1" start="14"><li class="c6 li-bullet-2"><span class="c1 c5">For the purposes of the access to and use of the </span><span class="c2 c5">Platform</span><span class="c1 c5">&nbsp;and Services</span><span class="c2 c5">&nbsp;the User</span><span class="c1 c5">&nbsp;may be required to sign up for an account within the </span><span class="c2 c5">Platform</span><span class="c1 c5">&nbsp;(the &quot;</span><span class="c4 c0">Account</span><span class="c1 c5">&quot;), and select a password and </span><span class="c2 c5">username (</span><span class="c2 c5">the &#39;&#39;</span><span class="c21 c5">User ID</span><span class="c1 c5">&#39;&#39;).</span></li></ol><p class="c7"><span class="c1 c5"></span></p><ol class="c8 lst-kix_list_8-1" start="15"><li class="c6 li-bullet-3"><span class="c2">You</span><span class="c1 c5">&nbsp;shall provide us with accurate, complete, and updated registration information about yourself</span><span class="c3 c2 c5">&nbsp;</span><span class="c1 c5">and to&nbsp;promptly notify </span><span class="c2 c5">The Fans</span><span class="c1 c5">&nbsp;in&nbsp;the event of&nbsp;any changes to&nbsp;any such information. </span></li></ol><p class="c16 c18 c36"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="16"><li class="c6 li-bullet-2"><span class="c1">You shall be&nbsp;solely responsible for the security and proper use of&nbsp;all User IDs, passwords or&nbsp;other security devices used in&nbsp;connection with the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, and shall take all reasonable steps to&nbsp;ensure that they are kept confidential and secure, are used properly and are not disclosed to&nbsp;or&nbsp;used by&nbsp;any other person or&nbsp;entity. You shall immediately inform </span><span class="c2">The Fans</span><span class="c1">&nbsp;if&nbsp;there is&nbsp;any reason to&nbsp;believe that the&nbsp;User&nbsp;ID, password or&nbsp;any other security device used in&nbsp;connection with the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, has or&nbsp;is&nbsp;likely to&nbsp;become known to&nbsp;someone not authorized to&nbsp;use&nbsp;it, or&nbsp;is&nbsp;being or&nbsp;is&nbsp;likely to&nbsp;be used in&nbsp;an&nbsp;unauthorized way. </span><span class="c2">The Fans</span><span class="c1">&nbsp;reserves the right (</span><span class="c15 c2 c12">at&nbsp;its sole discretion</span><span class="c1">) to&nbsp;request that you change your User ID, and you shall promptly comply with any such request.</span></li></ol><p class="c16 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="17"><li class="c6 li-bullet-3"><span class="c1">You are solely responsible for all activity in&nbsp;connection with access to&nbsp;the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, through your Account or&nbsp;using your User ID, and for the security of&nbsp;your computer systems, and in&nbsp;no&nbsp;event shall </span><span class="c2">The Fans</span><span class="c1">&nbsp;be&nbsp;liable for any loss or&nbsp;damages relating to&nbsp;such activity.</span></li></ol><p class="c16 c11 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="18"><li class="c6 li-bullet-3"><span class="c1">You may not transfer your Account to anyone else without our prior written permission. In the event of any dispute between two or more persons as to account ownership, you agree that </span><span class="c2">The Fans</span><span class="c1">&nbsp;shall be the sole arbiter of such dispute in its discretion and that </span><span class="c2">The Fans</span><span class="c1">&#39;s decision (</span><span class="c2 c12 c15">which may include termination or suspension of any Account subject to dispute</span><span class="c1">) shall be final and binding on all parties. </span><span class="c2">The Fans</span><span class="c1">&nbsp;may, in </span><span class="c2">its</span><span class="c1">&nbsp;sole discretion, refuse to open an Account, or limit the number of Accounts that you may hold or suspend or terminate any Account. </span></li></ol><p class="c16 c11 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="19"><li class="c6 li-bullet-3"><span class="c1">You hereby authorize us to make inquiries, whether directly or through third parties, that we consider necessary to verify your identity or protect you or us, or both, against fraud or other financial crime, and to take action we reasonably deem necessary based on the results of such inquiries. When we carry out these inquiries, you acknowledge and agree that your personal information may be disclosed to credit reference and fraud prevention or financial crime agencies and that these agencies may respond to our inquiries in full. This is an identity check only and should have no adverse effect on your credit rating, although we cannot and will not guarantee.</span></li></ol><p class="c7 c24"><span class="c0 c9"></span></p><ol class="c8 lst-kix_list_8-0" start="4"><li class="c14 li-bullet-1"><span class="c0 c9">CONTENT AND IP RIGHTS</span></li></ol><p class="c16 c11 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="20"><li class="c6 li-bullet-2"><span class="c1">The </span><span class="c2">Platform</span><span class="c1">&nbsp;and Services may contain materials, including information, Trademarks, data, text, editorial content, design elements, look and feel, formatting, graphics, images, photographs, videos, music, sounds and other content, which owned, operated, licensed, or controlled by </span><span class="c2">The Fans</span><span class="c1">&nbsp;and which is protected by copyright, trademark, trade secret, or other proprietary rights (collectively, &quot;</span><span class="c21">The Fans</span><span class="c0 c9">&nbsp;Content</span><span class="c1">&quot;). </span><span class="c2">The Fans</span><span class="c1">&nbsp;or its relevant suppliers, or licensors, retain all rights in such </span><span class="c2">The Fans</span><span class="c1">&nbsp;Content. </span><span class="c2">The Fans</span><span class="c1">&nbsp;grants you a limited, revocable, personal, non-transferable, non-sublicensable and non-exclusive right to view </span><span class="c2">The Fans</span><span class="c1">&nbsp;Content solely for your internal access to, and use of the </span><span class="c2">Platform</span><span class="c1">&nbsp;and Services.</span></li></ol><p class="c16 c18 c24"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="21"><li class="c6 li-bullet-2"><span class="c1">All </span><span class="c2">The Fans</span><span class="c1">&nbsp;Content is information of a general nature and does not address the circumstances of any particular individual or entity. Nothing in the </span><span class="c2">The Fans</span><span class="c1">&nbsp;Content constitutes a comprehensive or complete statement of the matters discussed or the law relating thereto. You alone assume the sole responsibility of evaluating the merits and risks associated with the access to, or use of, or both, of the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both, before making any decisions based on the information contained in </span><span class="c2">The Fans</span><span class="c1">&nbsp;Content.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="22"><li class="c6 li-bullet-3"><span class="c1">You retain any and all IP Rights you already hold under applicable law in materials, including information, data, text, editorial content, design elements, look and feel, formatting, graphics, images, photographs, videos, music, sounds and other content you upload, publish, and submit to or through the </span><span class="c2">Platform</span><span class="c1">&nbsp;or Services, or both (the &quot;</span><span class="c0 c9">User Content</span><span class="c1">&quot;), subject to the rights, licenses, and other terms of the Terms, including any underlying rights of other users or </span><span class="c2">The Fans</span><span class="c1">&nbsp;in the relevant content that you may use or modify.</span></li></ol><p class="c7"><span class="c3 c2"></span></p><ol class="c8 lst-kix_list_8-1" start="23"><li class="c6 li-bullet-4"><span class="c1">In connection with the User Content you affirm, represent, and warrant that you own or have all necessary IP Rights, licenses, consents, and permissions to use and authorize </span><span class="c2">The Fans</span><span class="c1">&nbsp;and users to use the User Content in the manner contemplated by the Terms.</span></li></ol><p class="c7"><span class="c3 c2"></span></p><ol class="c8 lst-kix_list_8-1" start="24"><li class="c6 li-bullet-2"><span class="c1">Because the law may or may not recognize certain IP Rights in any particular User Content, you should consult a lawyer if you want legal advice regarding your legal rights in a specific situation. You acknowledge and agree that you are responsible for knowing, protecting, and enforcing any IP Rights you hold, and that </span><span class="c2">The Fans</span><span class="c1">&nbsp;cannot do so on your behalf.</span></li></ol><p class="c7"><span class="c3 c2"></span></p><ol class="c8 lst-kix_list_8-1" start="25"><li class="c6 li-bullet-2"><span class="c1">Except as prohibited by any applicable law, you hereby waive, and you agree to waive, any moral rights (</span><span class="c15 c2 c12">including attribution and integrity</span><span class="c1">) that you may have in any User Content, even if it is altered or changed in a manner not agreeable to you. To the extent not waivable, you irrevocably agree not to exercise such rights (</span><span class="c15 c2 c12">if any</span><span class="c1">) in a manner that interferes with any exercise of the granted rights. You understand that you will not receive any fees, sums, consideration or remuneration for any of the rights granted in this section</span><span class="c2">, unless otherwise provided for herein</span><span class="c1">.</span></li></ol><p class="c7"><span class="c2 c3"></span></p><ol class="c8 lst-kix_list_8-1" start="26"><li class="c6 li-bullet-4"><span class="c1">You hereby grant to </span><span class="c2">The Fans</span><span class="c1">, and you agree to grant to </span><span class="c2">The Fans</span><span class="c1">, the non-exclusive, unrestricted, unconditional, unlimited, worldwide, irrevocable, perpetual, and royalty-free right and license to use, copy, record, distribute, reproduce, disclose, modify, display, publicly perform, transmit, publish, broadcast, translate, make derivative works of, and sell, re-sell or sublicense (</span><span class="c15 c2 c12">through multiple levels</span><span class="c1">), and otherwise exploit in any manner whatsoever, all or any portion of your User Content (</span><span class="c15 c2 c12">and derivative works thereof</span><span class="c1">), for any purpose whatsoever in all formats, on or through any media, software, formula, or medium now known or hereafter developed, and with any technology or devices now known or hereafter developed, and to advertise, market, and promote the same. You agree that the license includes the right to copy, analyze and use </span><span class="c2">any User</span><span class="c1">&nbsp;Content as </span><span class="c2">The Fans</span><span class="c1">&nbsp;may deem necessary or desirable for purposes of debugging, testing, or providing support or development services in connection with the </span><span class="c2">Platform</span><span class="c1">&nbsp;and Services and future improvements to the </span><span class="c2">Platform</span><span class="c1">&nbsp;and Services.</span></li></ol><p class="c7"><span class="c3 c2"></span></p><ol class="c8 lst-kix_list_8-1" start="27"><li class="c6 li-bullet-3"><span class="c2">The Fans</span><span class="c1">&nbsp;shall not be obliged to monitor or enforce your IP Rights to your User Content, but you grant us the right to protect and enforce our rights to your User Content, including by bringing and controlling actions in your name and on your behalf (</span><span class="c15 c2 c12">at </span><span class="c2 c22">The Fans</span><span class="c15 c2 c12">&#39;s cost and expense, to which you hereby consent and irrevocably appoint </span><span class="c2 c22">The Fans</span><span class="c15 c2 c12">&nbsp;as your attorney-in-fact, with the power of substitution and delegation, which appointment is coupled with an interest</span><span class="c1">).</span></li></ol><p class="c16 c18"><span class="c9 c2 c19"></span></p><ol class="c8 lst-kix_list_8-1" start="28"><li class="c6 li-bullet-3"><span class="c2 c12">You acknowledge and agree that </span><span class="c2">The Fans</span><span class="c2 c12">&nbsp;Content, </span><span class="c2">Platform</span><span class="c2 c12">&nbsp;and Services </span><span class="c2">fall</span><span class="c2 c12">&nbsp;into </span><span class="c2">The Fans</span><span class="c2 c12">&#39;s IP</span><span class="c2">&nbsp;</span><span class="c2 c12">or its relevant suppliers or licensors, and IP Rights to it and to all related materials to it is owned by </span><span class="c2">The Fans</span><span class="c2 c12">&nbsp;or its relevant suppliers or licensors. All uses of </span><span class="c2">The </span><span class="c2">Fans</span><span class="c2 c12">&#39;s</span><span class="c2 c12">&nbsp;IP shall inure to the benefit of </span><span class="c2">The Fans</span><span class="c1">.</span></li></ol><p class="c7 c24"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="29"><li class="c6 li-bullet-3"><span class="c2 c12">You shall use </span><span class="c2">The Fans</span><span class="c2 c12">&#39;s IP: (i) only in strict accordance with specifications and directions supplied by or on behalf of </span><span class="c2">The Fans</span><span class="c2 c12">&nbsp;from time to time; (ii) only in connection with access to, or use of, or both, the </span><span class="c2">Platform</span><span class="c2 c12">&nbsp;or Services, or both, and (iii) only in the form and style approved by </span><span class="c2">The Fans</span><span class="c1">.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="30"><li class="c6 li-bullet-3"><span class="c2 c12">You shall not include all or any portion of </span><span class="c2">The Fans</span><span class="c1">&#39;s IP in your IP or in the IP of any other person, or both.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="31"><li class="c6 li-bullet-2"><span class="c2 c12">You shall not use </span><span class="c2">The Fans</span><span class="c2 c12">&#39;s IP in a manner likely to cause confusion with, dilute </span><span class="c2">or damage</span><span class="c2 c12">&nbsp;the goodwill, reputation or image of </span><span class="c2">The Fans</span><span class="c1">.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="32"><li class="c6 li-bullet-3"><span class="c2 c12">You shall not register, attempt to register or lay common law claim to any </span><span class="c2">The Fans</span><span class="c2 c12">&nbsp;Content, </span><span class="c2">The Fans</span><span class="c2 c12">&#39;s IP, or any IP, confusingly similar to </span><span class="c2">The Fans</span><span class="c2 c12">&nbsp;Content or </span><span class="c2">The Fans</span><span class="c1">&#39;s IP, or both.</span></li></ol><p class="c16 c11 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="33"><li class="c6 li-bullet-3"><span class="c2 c12">No transfer, grant or license of IP Rights to </span><span class="c2">The Fans</span><span class="c2 c12">&nbsp;IP&#39;s or </span><span class="c2">The Fans</span><span class="c1">&nbsp;Content, or both, is made or is to be implied by the Terms except as may be expressly stated otherwise herein.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-0" start="5"><li class="c14 li-bullet-1"><span class="c0">PAYMENT PROCESSING METHOD, FEES</span><span class="c21">&nbsp;</span><span class="c0 c9">AND TAXES</span></li></ol><p class="c7 c24"><span class="c0 c9 c33"></span></p><ol class="c8 lst-kix_list_8-1" start="34"><li class="c6 li-bullet-3"><span class="c2 c5">Each User (</span><span class="c2 c5 c22">other than the applicable Content Maker</span><span class="c2 c5">) may subscribe through the Platform to the Account of the Content Maker. By subscribing to the Account of the Content Maker, the User may obtain the license to access the User Content of the relevant Content Maker (the </span><span class="c2">&quot;</span><span class="c21">Maker License</span><span class="c2">&quot;</span><span class="c2 c5">) on paid or unpaid basis (</span><span class="c2 c5 c22">as defined by the Content Maker within the relevant Account</span><span class="c2 c5">) (the </span><span class="c2">&quot;</span><span class="c21">User-Maker Agreement</span><span class="c2">&quot;</span><span class="c2 c5">). </span><span class="c2">The relevant Maker License shall be limited, personal, non-transferable, non-sublicensable and non-exclusive.</span></li></ol><p class="c27"><span class="c1 c5">&nbsp;</span></p><ol class="c8 lst-kix_list_8-1" start="35"><li class="c6 li-bullet-3"><span class="c1 c5">The User hereby acknowledges and agrees that each User-Maker Agreement is separate and distinct agreement between the User and Content Maker entered into pursuant to these Terms and The Fans shall not be the party to such User-Maker Agreement and shall not be responsible for the relevant User-Maker Agreement. </span></li></ol><p class="c18 c31"><span class="c1 c5"></span></p><ol class="c8 lst-kix_list_8-1" start="36"><li class="c6 li-bullet-4"><span class="c2 c5">Subject to applicable laws and regulations, the Terms, and various guidelines of The Fans, the Content Maker may establish the terms and conditions of the User-Maker Agreement, including, but not limited to, the pricing policy thereof, in compliance with which the Users will pay fo the access to the User Content of the relevant Content Maker (the </span><span class="c2">&quot;</span><span class="c21">Maker Fee</span><span class="c2">&quot;</span><span class="c1 c5">), through the relevant Content Maker&rsquo;s Account. </span></li></ol><p class="c7"><span class="c1 c5"></span></p><ol class="c8 lst-kix_list_8-1" start="37"><li class="c6 li-bullet-2"><span class="c2 c5">In consideration for the provision of the access to, and use of the Platform and Services, each Contant Maker shall pay to The Fans 20 (twenty) percent from all Maker Fee, paid to the relevant Content Maker (</span><span class="c2 c5 c22">plus applicable taxes, including, but not limited to, the VAT, if applicable</span><span class="c2 c5">) (</span><span class="c2">&quot;</span><span class="c21">The</span><span class="c2">&nbsp;</span><span class="c21">Fan Fee</span><span class="c2">&quot;</span><span class="c2 c5">), which shall be automatically deductible through the relevant third-party provider of payment methods (the </span><span class="c2">Maker Fee upon such deduction, the &quot;</span><span class="c21">Maker Profit</span><span class="c2">&quot;</span><span class="c1 c5">).</span></li></ol><p class="c7"><span class="c1 c5"></span></p><ol class="c8 lst-kix_list_8-1" start="38"><li class="c6 li-bullet-3"><span class="c2 c5">All Maker Fees shall be paid to the third-party provider of payment methods. The Users understand and accept that the mentioned payment methods are processed via third parties and any use of any of these payment methods may be subject to separate fees, terms and conditions provided through each of those methods. When applicable, by accessing or using the Platform or Services, or both, you acknowledge and agree to the terms and conditions, and the privacy policies of such methods. The Users hereby acknowledge and agree that we have no control over these payment methods as well as over the additional charges or expenses, which may arise within the process of payment of Maker Profit to the relevant Content Maker and </span><span class="c1 c5">we will have no liability to you or to any third party for any claims or damages in that regard.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="39"><li class="c6 li-bullet-2"><span class="c1">Users are solely responsible to pay any and all applicable sales, use, value-added and other taxes, duties, and assessments now or hereafter claimed or imposed by any governmental authority associated with your access to, and use of the Platform or Services, or both. You hereby authorize us to deduct any reasonable or necessary fee or tax. Except for income taxes levied on The Fans, you: (i) shall pay or reimburse us for all national, federal, state, local or other taxes and assessments of any jurisdiction, including value added taxes and taxes as required by international tax treaties, customs or other import or export taxes, and amounts levied in lieu thereof based on charges set, services performed or payments made hereunder, as are now or hereafter may be imposed under the authority of any national, state, local or any other taxing jurisdiction, and (ii) shall not be entitled to deduct the amount of any such taxes, duties or assessments from payments made to us pursuant to the Terms. &nbsp; </span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="40"><li class="c6 li-bullet-3"><span class="c2">These Terms do not affect any statutory rights of the Users under the Consumer Rights Act 2015 or any other applicable law.<br></span></li></ol><ol class="c8 lst-kix_list_8-0" start="6"><li class="c14 li-bullet-1"><span class="c0 c9">TERM AND TERMINATION</span></li></ol><p class="c16 c5 c18"><span class="c21 c9 c17"></span></p><ol class="c8 lst-kix_list_8-1" start="41"><li class="c6 li-bullet-2"><span class="c1 c5">These Terms are effective as set forth in section A hereof and continue in effect until terminated.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="42"><li class="c6 li-bullet-4"><span class="c1">Except to the extent we have agreed otherwise in a separate written agreement between you and The Fans, we may terminate your right to access or use, or both, the Platform or Services, or both, at any time for (i) your violation or breach of the Terms; (ii) your misuse or abuse of the Platform or Services, or both, or (iii) if allowing you to access or use, or both, the Platform or Services, or both, would violate any applicable local, state, provincial, national and other laws, rules and regulations or would expose The Fans to legal liability. We will use reasonable efforts to provide you notice of any such termination. Further, you agree that The Fans shall not be liable to you or any third party for any such termination of your right to access or use, or both, the Platform or Services, or both.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="43"><li class="c6 li-bullet-2"><span class="c2">Except to the extent you have agreed otherwise in a separate written agreement between you and an authorized officer of The Fans, you may terminate your access to, or use of, or both, the Platform or Services, or both, and the Terms at any time. At such time, you must delete your Account. In the event there is a separate agreement between you and The Fans governing your access to, and use of, or both, the Platform and Services, or both, and that agreement terminates or expires, the Terms (</span><span class="c2 c22">as unmodified by such agreement</span><span class="c1">) shall govern your access to, and use of, or both, the Platform and Services, or both, unless and until you delete your Account.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="44"><li class="c6 li-bullet-4"><span class="c2">All provisions of the Terms that by their nature should survive termination of the Terms shall survive (</span><span class="c2 c22">including all limitations on liability, releases, indemnification obligations, disclaimers of warranties, agreements to arbitrate, choices of law and judicial forum and intellectual property protections and licenses</span><span class="c1">).</span></li></ol><p class="c16 c11 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="45"><li class="c6 li-bullet-3"><span class="c2">You</span><span class="c2 c19">&nbsp;agree that The Fans, in its sole discretion, may suspend or terminate your Account (</span><span class="c2 c19 c22">or any part thereof</span><span class="c2 c19">) or access to, or use of, or both, the Platform or Services, or both, and remove and discard any content within the Platform, for any reason, including for lack of use or if The Fans believes that you have violated or acted inconsistently with the letter or spirit of these Terms. Any suspected fraudulent, abusive, infringing, or illegal activity that may be grounds for termination of your access to, or use of, or both, the Platform or Services, or both, may be referred to appropriate law enforcement authorities. The Fans may also in its sole discretion and at any time discontinue providing the Platform, or any part thereof, with or without notice. You agree that any termination of your access to, or use of, or both, the Platform or Services, or both, under any provision of these Terms may be effected without prior notice, and acknowledge and agree that The Fans may immediately deactivate or delete your Account and all related information and files in your Account. Further, you agree that The Fans will not be liable to you or any third party for any termination of your access to, or use of, or both, the Platform or Services, or both.</span></li></ol><p class="c31 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-0" start="7"><li class="c14 li-bullet-1"><span class="c0 c9">INDEMNIFICATION AND DISCLAIMER</span></li></ol><p class="c7 c26"><span class="c0 c9"></span></p><ol class="c8 lst-kix_list_8-1" start="46"><li class="c6 li-bullet-2"><span class="c2">YOU AGREE TO RELEASE, INDEMNIFY AND HOLD THE FANS AND ITS AFFILIATES AND THEIR OFFICERS, EMPLOYEES, CONTRACTORS, PARTNERS, DIRECTORS AND AGENTS (COLLECTIVELY, &quot;</span><span class="c21">THE FANS PARTIES</span><span class="c2">&quot;) HARMLESS FROM ANY AND ALL LOSSES, DAMAGES, EXPENSES, INCLUDING REASONABLE ATTORNEYS&#39; FEES, RIGHTS, CLAIMS, ACTIONS OF ANY KIND AND INJURY (</span><span class="c2 c22">INCLUDING DEATH</span><span class="c1">) ARISING OUT OF OR RELATING TO YOUR ACCESS OR USE, OR BOTH, OF THE PLATFORM OR SERVICES, OR BOTH, ANY YOUR USER CONTENT, YOUR CONNECTION TO THE PLATFORM, YOUR VIOLATION OF THE TERMS OR YOUR VIOLATION OF ANY RIGHTS OF ANOTHER. NOTWITHSTANDING THE FOREGOING, YOU WILL HAVE NO OBLIGATION TO INDEMNIFY OR HOLD HARMLESS ANY THE FANS PARTIES FROM OR AGAINST ANY LIABILITY, LOSSES, DAMAGES OR EXPENSES INCURRED AS A RESULT OF ANY ACTION OR INACTION OF SUCH THE FANS PARTIES. </span></li></ol><p class="c7 c24"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="47"><li class="c6 li-bullet-3"><span class="c1">YOUR ACCESS AND USE OF THE PLATFORM AND SERVICES IS AT YOUR OWN RISK. YOU UNDERSTAND AND AGREE THAT THE PLATFORM AND SERVICES ARE PROVIDED ON AN &quot;AS IS&quot; AND &quot;AS AVAILABLE&quot; BASIS AND THE FANS EXPRESSLY DISCLAIMS WARRANTIES, REPRESENTATIONS OR CONDITIONS OF ANY KIND, EITHER EXPRESS OR IMPLIED. THE FANS (AND THE FANS PARTIES) MAKES NO WARRANTY OR REPRESENTATION AND DISCLAIM ALL RESPONSIBILITY FOR WHETHER THE PLATFORM OR SERVICES: (I) WILL MEET YOUR REQUIREMENTS; (II) WILL BE AVAILABLE ON AN UNINTERRUPTED, TIMELY, SECURE, OR ERROR-FREE BASIS, OR (III) WILL BE ACCURATE, RELIABLE, COMPLETE, LEGAL, OR SAFE. THE FANS DISCLAIMS ALL OTHER WARRANTIES, REPRESENTATIONS OR CONDITIONS, EXPRESS OR IMPLIED, INCLUDING IMPLIED WARRANTIES, REPRESENTATIONS OR CONDITIONS OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, TITLE AND NON-INFRINGEMENT. THE FANS WILL NOT BE LIABLE FOR ANY LOSS OF ANY KIND FROM ANY ACTION TAKEN OR TAKEN IN RELIANCE ON MATERIAL OR INFORMATION, CONTAINED ON THE PLATFORM OR SERVICES, OR BOTH. WHILE THE FANS ATTEMPTS TO MAKE YOUR ACCESS TO AND USE OF THE PLATFORM OR SERVICES, OR BOTH, SAFE, THE FANS CANNOT AND DOES NOT REPRESENT OR WARRANT THAT THE PLATFORM, SERVICES OR THE FANS CONTENT ARE FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS. WE CANNOT GUARANTEE THE SECURITY OF ANY DATA THAT YOU DISCLOSE ONLINE. NO ADVICE OR INFORMATION, WHETHER ORAL OR OBTAINED FROM THE FANS OR THE FANS PARTIES OR THROUGH THE PLATFORM OR SERVICES, WILL CREATE ANY WARRANTY OR REPRESENTATION NOT EXPRESSLY MADE HEREIN. YOU ACCEPT THE INHERENT SECURITY RISKS OF PROVIDING INFORMATION AND DEALING ONLINE OVER THE INTERNET AND WILL NOT HOLD THE FANS RESPONSIBLE FOR ANY BREACH OF SECURITY.</span></li></ol><p class="c7 c24"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="48"><li class="c6 li-bullet-4"><span class="c1">WE WILL NOT BE RESPONSIBLE OR LIABLE TO YOU FOR ANY LOSS AND TAKE NO RESPONSIBILITY FOR, AND WILL NOT BE LIABLE TO YOU FOR THE FANS CONTENT, INCLUDING ANY LOSSES, DAMAGES, OR CLAIMS ARISING FROM: (I) USER ERROR, INCORRECTLY CONSTRUCTED TRANSACTIONS, OR MISTYPED ADDRESSES; (II) SERVER FAILURE OR DATA LOSS; (III) UNAUTHORIZED ACCESS OR USE, AND (IV) ANY UNAUTHORIZED THIRD-PARTY ACTIVITIES, INCLUDING THE USE OF VIRUSES, PHISHING, BRUTEFORCING OR OTHER MEANS OF ATTACK AGAINST THE PLATFORM OR SERVICES, OR BOTH. </span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="49"><li class="c6 li-bullet-2"><span class="c1">SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES IN CONTRACTS WITH CONSUMERS, THEREFORE, THE ABOVE EXCLUSION MAY NOT APPLY TO YOU.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-0" start="8"><li class="c14 li-bullet-1"><span class="c0 c9">LIMITATION OF LIABILITY </span></li></ol><p class="c7"><span class="c0 c9"></span></p><ol class="c8 lst-kix_list_8-1" start="50"><li class="c6 li-bullet-2"><span class="c2 c12">YOU EXPRESSLY UNDERSTAND AND AGREE THAT </span><span class="c2">THE FANS </span><span class="c2 c12">&nbsp;WILL NOT BE LIABLE FOR ANY INDIRECT, INCIDENTAL, SPECIAL, CONSEQUENTIAL, EXEMPLARY DAMAGES, OR DAMAGES FOR LOSS OF PROFITS INCLUDING BUT NOT LIMITED TO, LOSS IN VALUE, DAMAGES FOR LOSS OF GOODWILL, USE, DATA OR OTHER INTANGIBLE LOSSES (</span><span class="c2 c22 c12">EVEN IF </span><span class="c2 c22">THE FANS</span><span class="c2 c22 c12">&nbsp;HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES</span><span class="c2 c12">), WHETHER BASED ON CONTRACT, TORT, NEGLIGENCE, STRICT LIABILITY OR OTHERWISE, RESULTING FROM: (I) </span><span class="c2">ACCESS OR USE, OR BOTH, THE PLATFORM OR SERVICES, OR BOTH</span><span class="c2 c12">; (II) THE COST OF PROCUREMENT OF SUBSTITUTE GOODS AND PLATFORMS RESULTING FROM ANY GOODS, DATA, INFORMATION OR PLATFORMS PURCHASED OR OBTAINED OR MESSAGES RECEIVED OR TRANSACTIONS ENTERED INTO THROUGH OR FROM THE </span><span class="c2">PLATFORM OR SERVICES, OR BOTH</span><span class="c2 c12">; (III) UNAUTHORIZED ACCESS TO OR ALTERATION OF YOUR TRANSMISSIONS OR DATA; (IV) STATEMENTS OR CONDUCT OF ANY THIRD PARTY ON THE </span><span class="c2">PLATFORM</span><span class="c2 c12">, OR (V) ANY OTHER MATTER RELATING TO THE </span><span class="c2">PLATFORM</span><span class="c2 c12">&nbsp;OR SERVICES, OR BOTH. IN NO EVENT WILL </span><span class="c2">THE FANS</span><span class="c2 c12">&#39;S TOTAL LIABILITY TO YOU FOR ALL DAMAGES, LOSSES OR CAUSES OF ACTION EXCEED THE AMOUNT YOU HAVE PAID </span><span class="c2">THE FANS</span><span class="c1">&nbsp;IN THE LAST 6 (SIX) MONTHS, OR, IF GREATER, USD 100 (ONE HUNDRED DOLLARS).</span></li></ol><p class="c7 c24"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="51"><li class="c6 li-bullet-2"><span class="c2 c12">SOME JURISDICTIONS DO NOT ALLOW THE DISCLAIMER OR EXCLUSION OF CERTAIN WARRANTIES OR THE LIMITATION OR EXCLUSION OF LIABILITY FOR INCIDENTAL OR CONSEQUENTIAL DAMAGES. ACCORDINGLY, SOME OF THE ABOVE LIMITATIONS SET FORTH ABOVE MAY NOT APPLY TO YOU OR BE ENFORCEABLE WITH RESPECT TO YOU. IF YOU ARE DISSATISFIED WITH ANY PORTION OF THE </span><span class="c2">PLATFORM,</span><span class="c2 c12">&nbsp;OR SERVICES, OR BOTH, OR WITH THE TERMS, YOUR SOLE AND EXCLUSIVE REMEDY IS TO DISCONTINUE </span><span class="c2">ACCESS AND USE OF THE PLATFORM AND SERVICES</span><span class="c1">.</span></li></ol><p class="c27"><span class="c1">&nbsp;</span></p><ol class="c8 lst-kix_list_8-0" start="9"><li class="c14 li-bullet-1"><span class="c0 c9">GOVERNING LAW AND DISPUTE RESOLUTION </span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="52"><li class="c6 li-bullet-2"><span class="c2 c12">The</span><span class="c1">&nbsp;Terms shall be governed by and construed and interpreted in accordance with the laws of England and Wales, as to all matters, including matters of validity, construction, effect, enforceability, performance and remedies. Although the Platform and Services may be available in other jurisdictions, each user hereby acknowledges and agrees that such availability shall not be deemed to give rise to general or specific personal jurisdiction over The Fans in any forum outside England and Wales.</span></li></ol><p class="c7 c11"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="53"><li class="c6 li-bullet-3"><span class="c2">If a user has a potential legal dispute, claim or cause of action against The Fans (the &quot;</span><span class="c21">Claim</span><span class="c2">&quot;), the User shall first (</span><span class="c2 c22">prior to initiating any litigation proceedings</span><span class="c2">) contact The Fans by sending an email to: </span><span class="c35 c25 c5 c22">[email address] </span><span class="c2 c19">(</span><span class="c2 c19 c22">subject line:&nbsp;&quot;Dispute&quot;</span><span class="c2 c19">) </span><span class="c1">describing the nature of the potential dispute, claim or cause of action and providing all relevant documentation and evidence thereof. If so elected by The Fans, user shall use commercially reasonable efforts to negotiate a settlement of any such legal dispute, claim or cause of action within 60 (sixty) calendar days of the delivery of such email. Any such dispute, claim or cause of action that is not finally resolved by a binding, written settlement agreement within such 60 (sixty) calendar days shall be brought and resolved exclusively in accordance with the following provisions of this section 9.</span></li></ol><p class="c16 c11 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="54"><li class="c6 li-bullet-2"><span class="c2">Except as set forth in section 9.2, all Claims and disputes and controversies directly or indirectly arising &nbsp;out of or in connection with or directly or indirectly relating to the Terms or any of the matters or transactions contemplated by the Terms (f</span><span class="c2 c22">or the avoidance of doubt, including any claim seeking to invalidate, or alleging that, all or any part of the Terms is unenforceable, void or voidable</span><span class="c1">) shall be finally settled by the courts of England and Wales. Unless otherwise required by applicable law, the limitation period for Claims shall be 12 (twelve) months after the Claim arose.</span></li></ol><p class="c7 c24"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-0" start="10"><li class="c10"><span class="c0">NOTICES</span></li></ol><p class="c7"><span class="c1"></span></p><p class="c27"><span class="c2">You</span><span class="c2 c17">&nbsp;may contact us at the following contact details: </span></p><p class="c7 c24"><span class="c9 c2 c17"></span></p><p class="c27 c24"><span class="c21">Name</span><span class="c1">: THE FANS RESEARCH LABS LTD</span></p><p class="c27 c24"><span class="c21">Address</span><span class="c1">: 41 Devonshire Street, Ground Floor, London, United Kingdom, W1G 7AJ</span></p><p class="c27 c24"><span class="c21">Email</span><span class="c2">: </span><span class="c25 c5 c22 c35">[email address]</span></p><p class="c16 c18"><span class="c0 c9"></span></p><ol class="c8 lst-kix_list_8-0" start="11"><li class="c10"><span class="c0">MISCELLANEOUS PROVISIONS</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="55"><li class="c6 li-bullet-2"><span class="c2">You</span><span class="c2 c12">&nbsp;shall not assign, transfer, mortgage, charge, declare a trust of, or deal in any other manner with any or all of your rights and obligations under these Terms without prior written consent of </span><span class="c2">The Fans</span><span class="c2 c12">. </span><span class="c2">The Fans</span><span class="c1">&nbsp;may assign, transfer, mortgage, charge, declare a trust of, or deal in any other manner with any or all of its rights and obligations under these Terms without your prior written consent.</span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="56"><li class="c6 li-bullet-3"><span class="c2">These</span><span class="c2 c12">&nbsp;Terms set forth the entire agreement between you and </span><span class="c2">The Fans</span><span class="c2 c12">&nbsp;with regard to the subject matter hereof and supersede all prior or contemporaneous agreements and understandings, both written and oral, between the you and </span><span class="c2">The Fans</span><span class="c1">&nbsp;with respect to the subject matter hereof.</span></li></ol><p class="c16 c11 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="57"><li class="c6 li-bullet-3"><span class="c2 c12">Should any term, condition, provision or part of these Terms be found to be unlawful, invalid, illegal or unenforceable, </span><span class="c2">that</span><span class="c1">&nbsp;portion shall be deemed null and void and severed from these Terms for all purposes, but such illegality, or invalidity or unenforceability shall not affect the legality, validity or enforceability of the remaining parts of these Terms, and the remainder of these Terms shall remain in full force and effect, unless such would be manifestly inequitable or would serve to deprive either Party of a material part of what it bargained for in entering into these Terms. </span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="58"><li class="c6 li-bullet-4"><span class="c2 c12">No failure or delay on the part of </span><span class="c2">The Fans</span><span class="c2 c12">&nbsp;in the exercise of any power, right, privilege or remedy under the Terms shall operate as a waiver of such power, right, privilege or remedy; and no single or partial exercise of any </span><span class="c2">such</span><span class="c2 c12">&nbsp;power, right, privilege or remedy shall preclude any other or further exercise thereof or of any other power, right, privilege or remedy. </span><span class="c2">The Fans</span><span class="c2 c12">&nbsp;shall not be deemed to have waived any claim arising out of the Terms, or any power, right, privilege or remedy under the Terms, unless the waiver of such claim, power, right, privilege or remedy is expressly set forth in a written instrument duly executed and delivered on behalf of </span><span class="c2">The Fans</span><span class="c1">, and any such waiver shall not be applicable or have any effect except in the specific instance in which it is given. </span></li></ol><p class="c16 c11 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="59"><li class="c6 li-bullet-2"><span class="c2 c12">Nothing </span><span class="c2">contained</span><span class="c2 c12">&nbsp;in these Terms shall be deemed to constitute a partnership, joint venture or </span><span class="c2">employment</span><span class="c1">.</span></li></ol><p class="c16 c11 c18"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="60"><li class="c6 li-bullet-3"><span class="c2 c12">These Terms shall inure to the benefit of </span><span class="c2">The Fans</span><span class="c2 c12">, the </span><span class="c2">U</span><span class="c2 c12">sers, and their respective permitted successors, permitted assigns, permitted transferees and permitted delegates and shall be binding upon all of the foregoing persons </span><span class="c2">and</span><span class="c1">&nbsp;any person who may otherwise succeed to any right, obligation or liability under these Terms by operation of law or otherwise. </span></li></ol><p class="c7"><span class="c1"></span></p><ol class="c8 lst-kix_list_8-1" start="61"><li class="c6 li-bullet-3" id="h.gjdgxs"><span class="c2">The Fans</span><span class="c2 c12">&nbsp;shall not incur any liability or penalty for not performing any act or fulfilling any duty or obligation hereunder or in connection with the matters contemplated hereby by reason of any occurrence that is not within its control (</span><span class="c2 c22 c12">including any provision of any present or future law or regulation or any act of any governmental authority, any act of God or war or terrorism, any epidemic or pandemic, or the unavailability, disruption or malfunction of the Internet, the World Wide Web or any other electronic network, attack, or hack, or denial-of-service or other attack on the foregoing or any aspect thereof, or on the other software, networks and </span><span class="c2 c22">Platform</span><span class="c2 c22 c12">&nbsp;that enables </span><span class="c2 c22">The Fans</span><span class="c2 c22 c12">&nbsp;to provide the </span><span class="c2 c22">Platform</span><span class="c2 c22 c12">&nbsp;or Services, or both</span><span class="c2 c12">), it being understood that </span><span class="c2">The Fans</span><span class="c2 c12">&nbsp;shall use commercially reasonable efforts, consistent with accepted practices in the industries in which </span><span class="c2">The Fans</span><span class="c1">&nbsp;operates, as applicable, to resume performance as soon as reasonably practicable under the circumstances. </span></li></ol><p class="c7"><span class="c1"></span></p><div><p class="c13 c18"><span class="c9 c12 c32"></span></p><p class="c13 c18"><span class="c9 c32 c12"></span></p><p class="c18 c30"><span class="c9 c12 c29"></span></p>';
        $replaced = Str::replace('"', "'", $string);

        dd($replaced);

        auth()->setUser(User::find(1));




        $user= \user()
            ->loadCount(['informings' => fn($q) => $q->unread()])
            ->load(['subscribers' => fn($q) => $q->withCount(['user' => fn($q) => $q->online()]) ]);

        return $this->respondWithSuccess(

            new AccountInformationResource($user)
        );
















        $user = User::find(251);

        auth()->setUser(User::find(1));
            dd(env('VUE_APP_WS_HOST'));

        Artisan::call('cache:clear');
        return Subscription::query()
            ->where(
                'last_payment_date', '>=', Carbon::now()->subDays(30)->toDateTimeString()
            )
        ->get();
        $nickname = 'Macie_Wiza_N251';
//            dd(
//                Carbon::now()->addMonths(1)->toDateString(),
//                Carbon::now()->subMonth()->toDateString(),
//              "2022-2-11" > '2022-11-11'
//            );



        $user = User::query()
            ->sumReactionPost()
            ->with(['plans.subscription'])
            ->nickname($nickname)
            ->firstOrFail();

        return $this->respondWithSuccess(

            (new ContentCreatorResource($user))
                ->except('email', 'role', 'location', 'phone', 'address')
        );



        $user = User::query()
            ->sumReactionPost()
            ->countMediaPostCount()
            ->countPost($user->id, Auth::id())
            ->with(['plans.subscription'])
            ->findOrFail($user->id);

        return $this->respondWithSuccess(

            (new ContentCreatorResource($user))
                ->except('email', 'role', 'location', 'phone', 'address')
        );





      return  (new UserPostCollection($author
            ->posts()
            ->select('*')
            ->visibleUntil()
            ->orderBy('id', 'desc')
            ->allowedPrivatePostForSubscriber($author, \user())
        //    ->pinnedDisplayedAboveAll()
            ->with([
                'reactions',
                'others.entity.payments',
                'media.entity.payments',
                'user',
                'bookmarks',
                'allComments',
                'media.bookmarks',
                'others.bookmarks'
            ])->cursorPaginate(20, ['*'])
        )
        )->except('creator');



        $posts = Post::query()
            ->authorNotBanned()
            ->with([
                'user',
                'reactions',
                'bookmarks',
                'allComments:id,post_id',
                'others.entity.payments',
                'media.entity.payments',
                'media.bookmarks',
                'others.bookmarks'
            ])
            ->visibleUntil()
            ->algorithmSortingPosts($request->get('sortType'))
            ->filter($postFilter)
            ->paginate(20);

        $posts = $posts->setCollection($posts->getCollection()->allowPrivatePostFor(user()));

        return $this->respondWithSuccess(

            (new PostCollection($posts))->except('ppv_earned', 'ppv_user_paid')
        );
        dispatch(new ImageBlurJob($file, 'images'));

        dd(2);

   //return Storage::disk('s3')->temporaryUrl($file->poster, now()->addDays(7));


        $posts = Post::find(105069);

        return  $this->respondWithSuccess(new PostResource($posts));



          return $file->poster;
        $blurPath = $folder ."/".$blurName;





            return Storage::disk('s3')->temporaryUrl($file->blur, now()->addDays(7));



        return Storage::disk('s3')->response($file->url);

            dd(config('app.use_file_disk'));

          //  dd(env('HOST_BROADCASTING'));

        $user = User::find(1);

            dd(2);

        $author = User::find(5);

        auth()->setUser($user);

        app(PaymentGateway::class)
            ->withUser(user())
            ->getCustomer()
            ->charge(PaymentMethod::first(), 10);


            dd(3);
        $response = "eyJpdiI6ImM4QmdpNWtlK1kzTEMwcVNEQ2pMWUE9PSIsInZhbHVlIjoiejEvOHdJcG1wbjh5dVZIWDE4WnhNUT09IiwibWFjIjoiMzgyYjk5ZDlmZjVkYTFiZTkzYzllNDkzYmQyMjQ3MzcwOTc5Y2I1ZDE1MzZmZDI0OWY4NTBjZGMxNmFlNmFlMSIsInRhZyI6IiJ9";

        return redirect('/')
            ->withCookie(cookie("referral", $response, 30, null, null, false, false));




       // $message->owner->notify(new PromotionNotification($message));


        dd(2);


        $posts = Post::query()
     //       ->authorNotBanned()

             ->with([
                'user',
                'reactions',
                'bookmarks',
                'allComments:id,post_id',
                'others.entity.payments',
                'media.entity.payments'
            ])
            ->filter($postFilter)
            ->paginate(10);


      //  $posts = $posts->setCollection($posts->getCollection()->allowPrivatePostFor(user()));




        $posts = $posts->setCollection($posts->getCollection()->filter(function($post) use ($user){
          //  return $post->id > 2;
                    dd(!($post->access == 'private' and  !$post->user->isMySubscribed($user)),
                        !($post->access == PostType::PRIVATE and !(Auth::check() and $post->user->isMySubscribed($user)))
                    );

                return !($post->access == 'private' and  !$post->user->isMySubscribed($user));

            if($post->access == 'private' and  !$post->user->isMySubscribed($user)) {
                return false;
            }
            return true;
        })->values()
        );


      //  return $posts->paginate(20);



        return $this->respondWithSuccess(

            new PostCollection($posts)

        );




        dd(2);

        $request->request->add(['plan_id' => 3001]); //add request

        $paymentMethod = PaymentMethod::first();
        $post = Post::find(1);

        $subscribe = User::find(143);


//        //1  payment  logic
        (new SubscribeEntityPayment($subscribe))
            ->purpose('Subscribe')
            ->payload(['plan_id' => 2])
            ->isTransaction()
            ->historyType(HistoryType::SUBSCRIPTION)
            ->pay(new PaymentHandler($paymentMethod));



        dd(2);







        (new MediaEntityPPVPayment($post))
            ->purpose('Unlock post')
            ->transaction()
            ->historyType(HistoryType::POST)
            ->pay(new PaymentHandler($paymentMethod));






















        dd('done');




//300001
        $file = File::find(300001);


       return $file
           ->isUnlockedFor(2);




        dd(2);



        $interactions = Interaction::query()
            ->loadInteractionable()
            ->where('user_id',1)
            ->paginate(20);

        return new InteractionCollection($interactions);




        $posts = History::with(['historyable' => function($query) {
          //  $query->limit(15);
        }])->
            cursorPaginate(20);
            // todo 1
        return  new TestCollection($posts);




//        $grouped_by_date = $posts->mapToGroups(function ($post) {
//            return [$post->historyable_type => $post];
//        });
//        $posts_by_date = $posts->setCollection($grouped_by_date);


        //return   $posts_by_date ;

//        return  $posts_by_date->has('App\\Models\\Subscription') ? $posts_by_date->get('App\\Models\\Subscription')->map(function ($f) {
//            return   new SubscriptionResource($f->historyable) ;
//        }): [];
//


        return new TestCollection($posts);
            return $posts_by_date;




      $v =  History::query()
          ->take(400)
   //       ->groupBy('historyable_type')
         // ->groupBy('historyable_id')
      ->get()->groupBy('historyable_type');

return $v;
       $dd =  collect($v)->groupBy('historyable_type');


        return  $dd->get('App\\Models\\Subscription');

      return  new TestCollection($v);

        $posts = Post::allowed()
            ->with([
                'user',
                'reactions',
                'bookmarks',
                'allComments:id,post_id',
                'media.entity.payments' => fn($query) => $query->hasPaymentFor(1)])
            ->filter($postFilter)
          //  ->orderBy('shows', 'desc')
       //     ->whereRaw('RAND()<(SELECT ((?/COUNT(*))*10) FROM `posts`)', [2])->orderByRaw('RAND()')->limit(2)
            ->paginate(10)
            ->onEachSide(1);

        return $this->respondWithSuccess(

            new PostCollection($posts)

        );







    $ex = 'file_example.mp4';
    $v =     FFMpeg::fromDisk('video')


            ->open($ex) //locally stored (storage/app) specify a file name

            ->getFrameFromSeconds(3)

            ->export()

            ->toDisk('images')
           ->save('FrameAt10sec.png')->get();


    dd($v);



        $user = User::find(1502);

        $res = 316;



        $chat = app(ConversationSupport::class);

        return $chat->join($user, null, '');
        //  dd($chat);

        return $user->chatFor(316) ?? 'not has';


        return $user->chatFor(User::find(316));


        dd(Carbon::parse('2020-11-24'));


        $to   = Carbon::now()->addDays(1);
        $from = Carbon::now()->subDays($to->dayOfYear - 2);


        $post = Post::first();

        $earnings = History::query()
            ->earningGraphs($from, $to)
            ->type(Post::class)
            ->typeId(1)
            ->get()
            ->keyBy('date');
        return app(GenerateGraphDateAction::class)->handler($from, $to, $earnings);
       return $da;


        return app(PostStatisticAction::class)->handler($user, $from, $to);




     //   return $user->accountManagers;


       return (new ProfileResource($user));


        return new AccountInformationResource($user);





       dd($this-> solution('abc'));


       $to   = Carbon::now()->addDays(1);
       $from = Carbon::now()->subDays($to->dayOfYear - 2);



        $data = app(GraphGenerateAction::class)->execute();
    return $this->respondWithSuccess($data);

  //  return $this->respondWithSuccess($userDate);


        $user  = User::query()
                ->interactionStatistics()
                ->first();

            return $user;





        dd('done');


        $from = '2020-11-24';
        $to =  '2022-11-26';

        $user = User::with(['subscribers' => function($q) {

             $q->whereNotNull('subscriber_group_id');

        }])->find(1);



        return $user->chatFor(579) ;
















    }

    function percentage($percentage, $of)
    {
        $percent = $percentage / $of;
        return  number_format( $percent * 100, 0 );
    }

    function getPercentOfNumber($number, $percent){
        return ($percent / 100) * $number;
    }
}
