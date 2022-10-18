<?php

namespace Database\Seeders;

use App\Enums\PageType;
use App\Models\InterfaceImage;
use App\Models\InterfaceText;
use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class WebsiteSeeder extends Seeder
{

    public function run()
    {
        $this -> clear();


        // Pages and interface texts
        echo "==========> Generating data Pages and Interface \n";
        foreach ($this -> pages() as $page) {

            $pageModel = Page::factory() -> create($page['page']);

            if(!isset($page['texts'])) {
                continue;
            }

            foreach ($page['texts'] as  $text) {

               $interface = InterfaceText::create(array_merge($text, ['page_id' =>  $pageModel->id]));


               if($image = $this->images($interface->key)) {

                   InterfaceImage::create(array_merge($image, ['interface_text_id' => $interface->id]));
               }

            }
        }
    }

    private function pages(): array {

        return [
            [
                'page' => [
                    'name' => 'Home page',
                    'key' => PageType::LANDING_HEADER,
                    'title' => ['en' => 'Title What is LetFans ?', 'ru' => 'Заглавие что такое фанс ?'],
                    'meta_description' => ['en' => 'meta_description', 'ru' => 'meta_description'],
                    'meta_tags'        => ['en' => 'meta_tags', 'ru' => 'meta_tags'],
                    'body'  => '',
                    'type' => PageType::DEFAULT

                ],

                'texts' => [
                    ['name' => '',
                        'text' => [
                        'en' => 'Duis eget volutpat lectus. Donec vestibulum orci at urna molestie fermentum sit amet vel tortor. In hac habitasse platea dictumst. Fusce iaculis tellus non leo ornare, in accumsan ex mollis. Mauris ut sollicitudin arcu, at tincidunt quam.',
                        'ru' => 'Фильму нужна кровать выходного дня. Пока вход орков в урну досады не Говорят, что на этой улице он жил. Стрелы земли не украшают льва, в слое мягком. Mauris ut sollicitudin arcu, at tincidunt quam'
                    ],
                        'key' => 'header_text', 'length_limit' => 120],


                ],
            ],

            [
                'page' => [
                    'name' => 'Home page',
                    'key' =>  PageType::LANDING_KPI,
                    'title' => ['en' => 'Title KPI', 'ru' => 'Заглавие KPI'],
                    'meta_description' => ['en' => 'meta_description', 'ru' => 'meta_description'],
                    'meta_tags'        => ['en' => 'meta_tags', 'ru' => 'meta_tags'],
                    'type' => PageType::DEFAULT,
                    'body'  => [
                        'en' => 'Duis eget volutpat lectus. Donec vestibulum orci at urna molestie fermentum sit amet vel tortor. In hac habitasse platea dictumst. Fusce iaculis tellus non leo ornare, in accumsan ex mollis. Mauris ut sollicitudin arcu, at tincidunt quam.',
                        'ru' => 'На выходных нужна домашняя работа. Donecvestibulum orciat urna molestiefermentum sit ametvel tortor. В этом жилище было сказано. Стрела не должна украшать льва, в слое мягком. Mauris ut sollicitudin arcu, at tincidunt quam.'
                    ],

                ],
                'texts' => [
                    ['name' => '9876', 'text' =>  ['ru' => 'Какой-то KPI', 'en'=> 'Some kind of KPI'], 'key' => 'kpi_1', 'length_limit' => 20],
                    ['name' => '9876', 'text' =>  ['ru' => 'Какой-то KPI', 'en'=> 'Some kind of KPI'], 'key' => 'kpi_2', 'length_limit' => 20],
                    ['name' => '9876', 'text' =>  ['ru' => 'Какой-то KPI', 'en'=> 'Some kind of KPI'], 'key' => 'kpi_3', 'length_limit' => 20],
                    ['name' => '9876', 'text' =>  ['ru' => 'Какой-то KPI', 'en'=> 'Some kind of KPI'], 'key' => 'kpi_4', 'length_limit' => 20],


                ],
            ],

            [
                'page' => [
                    'name' => 'Home page',
                    'key' => PageType::LANDING_CONTENT,
                    'title' => ['en' => 'Why you should join', 'ru' => 'Почему вы должны присоединиться?'],
                    'meta_description' => ['en' => 'meta_description', 'ru' => 'meta_description'],
                    'meta_tags'        => ['en' => 'meta_tags', 'ru' => 'meta_tags'],
                    'type' => PageType::DEFAULT

                ],
                'texts' => [
                    ['name' => ['en' => 'Duis eget volutpat lectus.', 'ru' => 'Фильму нужна кровать выходного дня.'],
                        'text' => [
                            'ru' => 'Фильму нужна кровать выходного дня. До входа оратора, урны досады, беды быть любимым или мучителя. Говорят, что на этой улице он жил. Стрелы земли не украшают льва, в слое мягком. Mauris ut sollicitudin arcu, at tincidunt quam',
                            'en'=> 'Duis eget volutpat lectus. Donec vestibulum orci at urna molestie fermentum sit amet vel tortor. In hac habitasse platea dictumst. Fusce iaculis tellus non leo ornare, in accumsan ex mollis. Mauris ut sollicitudin arcu, at tincidunt quam.'
                        ],
                        'key' => 'should_join_text_1', 'length_limit' => 120],
                    ['name' => ['en' => 'Duis eget volutpat lectus.', 'ru' => 'Фильму нужна кровать выходного дня.'],
                        'text' => [
                            'ru' => 'Фильму нужна кровать выходного дня. До входа оратора, урны досады, беды быть любимым или мучителя. Говорят, что на этой улице он жил. Стрелы земли не украшают льва, в слое мягком. Mauris ut sollicitudin arcu, at tincidunt quam',
                            'en'=> 'Duis eget volutpat lectus. Donec vestibulum orci at urna molestie fermentum sit amet vel tortor. In hac habitasse platea dictumst. Fusce iaculis tellus non leo ornare, in accumsan ex mollis. Mauris ut sollicitudin arcu, at tincidunt quam.'
                        ],
                        'key' => 'should_join_text_2', 'length_limit' => 120],
                ]
            ],

            [
                'page' => [
                    'name' => 'Home page',
                    'key' => PageType::LANDING_FOOTER,
                    'title' => ['en' => 'How it works?', 'ru' => 'Как это работает?'],
                    'meta_description' => ['en' => 'meta_description', 'ru' => 'meta_description'],
                    'meta_tags'        => ['en' => 'meta_tags', 'ru' => 'meta_tags'],
                    'type' => PageType::DEFAULT

                ],
                'texts' => [
                    ['name' => ['en' => 'Duis eget volutpat lectus.', 'ru' => 'Фильму нужна кровать выходного дня.'],
                        'text' => [
                            'ru' => 'Фильму нужна кровать выходного дня. До входа оратора, урны досады, беды быть любимым или мучителя. Говорят, что на этой улице он жил. Стрелы земли не украшают льва, в слое мягком. Mauris ut sollicitudin arcu, at tincidunt quam',
                            'en'=> 'Duis eget volutpat lectus. Donec vestibulum orci at urna molestie fermentum sit amet vel tortor. In hac habitasse platea dictumst. Fusce iaculis tellus non leo ornare, in accumsan ex mollis. Mauris ut sollicitudin arcu, at tincidunt quam.'
                        ],
                        'key' => 'how_work_text_1', 'length_limit' => 120],
                    ['name' => ['en' => 'Duis eget volutpat lectus.', 'ru' => 'Фильму нужна кровать выходного дня.'],
                        'text' => [
                            'ru' => 'Фильму нужна кровать выходного дня. До входа оратора, урны досады, беды быть любимым или мучителя. Говорят, что на этой улице он жил. Стрелы земли не украшают льва, в слое мягком. Mauris ut sollicitudin arcu, at tincidunt quam',
                            'en'=> 'Duis eget volutpat lectus. Donec vestibulum orci at urna molestie fermentum sit amet vel tortor. In hac habitasse platea dictumst. Fusce iaculis tellus non leo ornare, in accumsan ex mollis. Mauris ut sollicitudin arcu, at tincidunt quam.'
                        ],
                        'key' => 'how_work_text_2', 'length_limit' => 120],
                    ['name' => ['en' => 'Duis eget volutpat lectus.', 'ru' => 'Фильму нужна кровать выходного дня.'],
                        'text' => [
                            'ru' => 'Фильму нужна кровать выходного дня. До входа оратора, урны досады, беды быть любимым или мучителя. Говорят, что на этой улице он жил. Стрелы земли не украшают льва, в слое мягком. Mauris ut sollicitudin arcu, at tincidunt quam',
                            'en'=> 'Duis eget volutpat lectus. Donec vestibulum orci at urna molestie fermentum sit amet vel tortor. In hac habitasse platea dictumst. Fusce iaculis tellus non leo ornare, in accumsan ex mollis. Mauris ut sollicitudin arcu, at tincidunt quam.'
                        ],
                        'key' => 'how_work_text_3', 'length_limit' => 120],
                    ['name' => ['en' => 'Duis eget volutpat lectus.', 'ru' => 'Фильму нужна кровать выходного дня.'],
                        'text' => [
                            'ru' => 'Фильму нужна кровать выходного дня. До входа оратора, урны досады, беды быть любимым или мучителя. Говорят, что на этой улице он жил. Стрелы земли не украшают льва, в слое мягком. Mauris ut sollicitudin arcu, at tincidunt quam',
                            'en'=> 'Duis eget volutpat lectus. Donec vestibulum orci at urna molestie fermentum sit amet vel tortor. In hac habitasse platea dictumst. Fusce iaculis tellus non leo ornare, in accumsan ex mollis. Mauris ut sollicitudin arcu, at tincidunt quam.'
                        ],
                        'key' => 'how_work_text_4', 'length_limit' => 120],
                ]
            ],



            [
                'page' => [
                    'name' => 'Content pages',
                    'key' => PageType::CONTENT_PAGE_POLICY,
                    'title' => ['en' => 'Privacy Policy', 'ru' => 'Политика конфиденциальности'],
                    'meta_description' => ['en' => 'meta_description', 'ru' => 'meta_description'],
                    'meta_tags'        => ['en' => 'meta_tags', 'ru' => 'meta_tags'],
                    'type' => PageType::DEFAULT
                ],

                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => $this->text('ru'), 'en'=> $this->text('en')], 'key' => 'privacy_policy_section_1', 'length_limit' => 20],
                    ['name' => '', 'text' =>  ['ru' => $this->text('ru'), 'en'=> $this->text('en')], 'key' => 'privacy_policy_section_2', 'length_limit' => 20],

                ],
            ],
            [
                'page' => [
                    'name' => 'Content pages',
                    'key' => PageType::CONTENT_PAGE_FAQ,
                    'title' => ['en' => 'F.A.Q', 'ru' => 'F.A.Q'],
                    'meta_description' => ['en' => 'meta_description', 'ru' => 'meta_description'],
                    'meta_tags'        => ['en' => 'meta_tags', 'ru' => 'meta_tags'],
                    'type' => PageType::DEFAULT
                ],

                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => $this->text('ru'), 'en'=> $this->text('en')], 'key' => 'terms_use', 'length_limit' => 20],
                ],
            ],
            [
                'page' => [
                    'name' => 'Content pages',
                    'key' => PageType::CONTENT_PAGE_SUPPORT,
                    'title' => ['en' => 'Support', 'ru' => 'Поддержка'],
                    'meta_description' => ['en' => 'meta_description', 'ru' => 'meta_description'],
                    'meta_tags'        => ['en' => 'meta_tags', 'ru' => 'meta_tags'],
                    'type' => PageType::DEFAULT
                ],

                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => $this->text('ru'), 'en'=> $this->text('en')], 'key' => 'support', 'length_limit' => 20],
                ],
            ],
            [
                'page' => [
                    'name' => 'Content pages',
                    'key' => PageType::CONTENT_PAGE_TERM_USE,
                    'title' => ['en' => 'Terms of Use', 'ru' => 'Условия использования'],
                    'meta_description' => ['en' => 'meta_description', 'ru' => 'meta_description'],
                    'meta_tags'        => ['en' => 'meta_tags', 'ru' => 'meta_tags'],
                    'type' => PageType::DEFAULT
                ],

                'texts' => [
                    ['name' => '', 'text' =>  ['ru' => $this->text('ru'), 'en'=> $this->text('en')], 'key' => 'terms_og_use', 'length_limit' => 20],
                ],
            ],


            [
                'page' => [
                    'name' => 'Content pages',
                    'key' => PageType::CONTENT_PAGE_500,
                    'title' => ['en' => '500', 'ru' => '500'],
                    'meta_description' => ['en' => 'meta_description', 'ru' => 'meta_description'],
                    'meta_tags'        => ['en' => 'meta_tags', 'ru' => 'meta_tags'],
                    'type' => PageType::DEFAULT
                ],

                'texts' => [
                    ['name' => ['en' => 'Server problems', 'ru' => 'Проблемы с сервером'], 'text' =>  ['ru' => 'Приносим свои извинения, попробуйте посетить нас позже…', 'en'=> 'We apologize, please try to visit us later…'], 'key' => 'error_page_500', 'length_limit' => 20],
                ],
            ],
            [
                'page' => [
                    'name' => 'Content pages',
                    'key' => PageType::CONTENT_PAGE_400,
                    'title' => ['en' => '400', 'ru' => '400'],
                    'meta_description' => ['en' => 'meta_description', 'ru' => 'meta_description'],
                    'meta_tags'        => ['en' => 'meta_tags', 'ru' => 'meta_tags'],
                    'type' => PageType::DEFAULT
                ],

                'texts' => [
                    ['name' => ['en' => 'Server problems', 'ru' => 'Проблемы с сервером'], 'text' =>  ['ru' => 'Приносим свои извинения, попробуйте посетить нас позже…', 'en'=> 'We apologize, please try to visit us later…'], 'key' => 'error_page_400', 'length_limit' => 20],
                ],
            ],

            //custom page

            [
                'page' => [
                    'name' => 'Custom pages',
                    'key' => NULL,
                    'title' => ['en' => 'Custom page', 'ru' => 'Пользовательские страницы'],
                    'meta_description' => ['en' => 'meta_description', 'ru' => 'meta_description'],
                    'meta_tags'        => ['en' => 'meta_tags', 'ru' => 'meta_tags'],
                    'type' => PageType::CUSTOM
                ],

                'texts' => [
                    ['name' => ['en' => 'Lorem ipsum dolor sit amet', 'ru' => 'Багато болю – це багато'], 'text' =>  ['ru' => $this->text('ru'), 'en'=>  $this->text('en')], 'key' => NULL, 'length_limit' => 120],
                ],
            ],
            [
                'page' => [
                    'name' => 'Custom pages Sed odio morbi quis commodo',
                    'key' => NULL,
                    'title' => ['en' => 'Sed odio morbi quis commodo', 'ru' => 'Пользовательские страницы 1'],
                    'meta_description' => ['en' => 'meta_description', 'ru' => 'meta_description'],
                    'meta_tags'        => ['en' => 'meta_tags', 'ru' => 'meta_tags'],
                    'type' => PageType::CUSTOM
                ],

                'texts' => [
                    ['name' => ['en' => 'Sed odio morbi quis commodo', 'ru' => 'Пользовательские страницы 1'], 'text' =>  ['ru' => $this->text('ru'), 'en'=>  $this->text('en')], 'key' => NULL, 'length_limit' => 120],
                ],
            ],
            [
                'page' => [
                    'name' => 'Custom pages Sed odio morbi quis commodo',
                    'key' => NULL,
                    'title' => ['en' => 'Sed odio morbi quis commodo', 'ru' => 'Пользовательские страницы 3'],
                    'meta_description' => ['en' => 'meta_description', 'ru' => 'meta_description'],
                    'meta_tags'        => ['en' => 'meta_tags', 'ru' => 'meta_tags'],
                    'type' => PageType::CUSTOM
                ],

                'texts' => [
                    ['name' => ['en' => 'Sed odio morbi quis commodo', 'ru' => 'Пользовательские страницы 3'], 'text' =>  ['ru' => $this->text('ru'), 'en'=>  $this->text('en')], 'key' => NULL, 'length_limit' => 120],
                ],
            ],
        ];
    }


    private function clear() {

        Schema::disableForeignKeyConstraints();
        DB::table('interface_images') -> truncate();
        DB::table('interface_texts') -> truncate();
        DB::table('pages') -> truncate();
    }

    protected function text($lang) {

        if($lang  === 'en') {
            return File::get(base_path('database/text_en.txt'));
        }else {
            return File::get(base_path('database/text_ru.txt'));
        }
    }

    protected function images($key) {

        return [
             'kpi_1' => ['name' => 'kpi_1', 'url' =>  '/images/kpi.png'],
             'kpi_2' => ['name' => 'kpi_2', 'url' =>  '/images/kpi_2.jpeg'],
             'kpi_3' => ['name' => 'kpi_3', 'url' =>  '/images/kpi_3.jpeg'],
             'kpi_4' => ['name' => 'kpi_4', 'url' =>  '/images/kpi_4.jpeg'],

             'should_join_text_1'   => ['name' => 'kpi_img_1',     'url' =>  '/images/kpi_img_1.png'],
             'should_join_text_2'   => ['name' => 'landing_img_2', 'url' =>  '/images/landing_img_2.png'],

             'how_work_text_1'   => ['name' => 'landing_img_3',     'url' =>  '/images/landing_img_3.png'],
             'how_work_text_2'   => ['name' => 'landing_img_4',     'url' =>  '/images/landing_img_4.png'],
             'how_work_text_3'   => ['name' => 'landing_img_5',     'url' =>  '/images/landing_img_5.png'],
             'how_work_text_4'   => ['name' => 'landing_img_6',     'url' =>  '/images/landing_img_6.png'],



        ][$key] ?? null;
    }


}
