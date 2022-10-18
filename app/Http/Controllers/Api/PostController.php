<?php

namespace App\Http\Controllers\Api;

use App\Enums\FileType;
use App\Enums\HistoryType;
use App\Enums\PostType;
use App\Enums\TrackFnType;
use App\Exceptions\PaymentFailedException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\General\UnlockRequest;
use App\Http\Requests\Post\PostRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Resources\Post\PostCollection;
use App\Http\Resources\Post\PostResource;
use App\Models\File;
use App\Models\Post;
use App\Models\Subscription;
use App\Services\Actions\AttachFileAction;
use App\Services\Actions\ErrorHandlerAction;
use App\Services\Actions\PostAddAction;
use App\Services\Actions\PostCountMediaFileAction;
use App\Services\Actions\PostUpdateAction;
use App\Services\Actions\TrackFnsAction;
use App\Services\Filters\PostFilter;
use App\Services\Payments\PaymentHandler\Entity\MediaEntityPPVPayment;
use App\Services\Payments\PaymentHandler\PaymentHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends BaseController
{

    public function __invoke(PostFilter $postFilter, Request $request) {

       $exists = Subscription::where('user_id', Auth::id())->first();

       $posts = Post::query()
           ->authorNotBanned()
           ->with([
               'user.subscribers',
               'reactions',
               'bookmarks',
               'allComments:id,post_id',
               'others.entity.payments',
               'media.entity.payments',
               'media.bookmarks',
               'others.bookmarks'
           ])
           ->visibleUntil()
           ->when($exists == null, fn($q) => $q->whereIn('id', getHardcoverPost()))
           ->when($exists != null, fn($q) => $q->algorithmSortingPosts(PostType::SUBSCRIPTIONS ))
        //   ->algorithmSortingPosts($request->get('sortType'))
           ->filter($postFilter)
           ->paginate($this->perPage());

    //   $posts = $posts->setCollection($posts->getCollection()->allowPrivatePostFor(user()));

        return $this->respondWithSuccess(

            (new PostCollection($posts))
        );
    }

    public function show(Post $post) {

        $post->load([
            'user',
            'reactions',
            'bookmarks',
            'allComments:id,post_id',
            'others.entity.payments',
            'media.entity.payments',
            'media.bookmarks',
            'others.bookmarks'
        ]);

        return $this->respondWithSuccess(

           (new PostResource($post))->except('interested', 'clickthroughs', 'shows', 'is_pinned')
        );
    }

    public function store(PostRequest $request, PostAddAction $postAddAction, PostCountMediaFileAction $postCountMediaFileAction, AttachFileAction $attachFileAction) {

        $post = $postAddAction->execute($request->getDto());

       // $post->addAttachments($request->file('attachments'));

        $attachFileAction->execute($post->id, $request->get('attachments'), FileType::MODEL_POST);

        $postCountMediaFileAction->handler($post);

        $post->load([
            'bookmarks',
            'user',
            'others.entity.payments',
            'media.entity.payments',
            'media.bookmarks',
            'others.bookmarks'
        ]);

        return $this->respondWithSuccess(

            (new PostResource($post))->except('interested', 'clickthroughs', 'shows', 'is_pinned')
        );
    }

    public function update(PostUpdateRequest $request, Post $post, PostUpdateAction $postUpdateAction) {

        $this->authorize('update', $post);

        $post->load([
            'user',
            'reactions',
            'bookmarks',
            'allComments:id,post_id',
            'others.entity.payments',
            'media.entity.payments',
            'media.bookmarks',
            'others.bookmarks'
        ]);

        return $this->respondWithSuccess(

            (new PostResource($postUpdateAction->execute($request->getDto(), $post)))->except('interested', 'clickthroughs', 'shows', 'is_pinned', 'reactions')
        );
    }

    public function delete(Post $post) {

        $this->authorize('delete', $post);

        $post->delete();

        return $this->respondOk('The post deleted successfully');
    }

    public function unlock(Post $post, UnlockRequest $request) {

        try {

            (new MediaEntityPPVPayment($post))
                ->purpose('Unlock post')
                ->isTransaction()
                ->historyType(HistoryType::POST)
                ->pay(new PaymentHandler($request->paymentMethod()));

            $post->load([
                'user',
                'reactions',
                'bookmarks',
                'allComments:id,post_id',
                'others.entity.payments',
                'media.entity.payments',
                'media.bookmarks',
                'others.bookmarks'
            ]);

            app(TrackFnsAction::class)->handler([TrackFnType::PPV, TrackFnType::CONFIRM], \user()->external_tracker_id, $post->ppv_price);


            return $this->respondWithSuccess(

                new PostResource($post)
            );

        }catch (PaymentFailedException $exception) {

            return $this->respondError(

                app(ErrorHandlerAction::class)->handler($exception->getMessage())
            );
        }

    }
}
