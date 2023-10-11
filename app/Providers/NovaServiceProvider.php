<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Nova;

use Laravel\Nova\ServingNova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Illuminate\Http\Request;
use Laravel\Nova\Menu\Menu;
use Laravel\Nova\Menu\MenuItem;
use Laravel\Nova\Menu\MenuSection;
use Symfony\Component\Routing\Route;
use Wdelfuego\Nova4\CustomizableFooter\Footer;
use App\Nova\User;
use App\Nova\Analyzes;
use App\Nova\Survey;
use App\Nova\SurveysCategories;
use App\Nova\FeedbackRegister;
use App\Nova\OldFeedbackRegister;
use App\Nova\StartInfo;
use App\Nova\UserAddNewCity;
use App\Nova\SearchResult;
use App\Nova\Admin;
use App\Nova\Moderators;
use App\Nova\NewsAndSales;
use App\Nova\InfoArchimedMedical;
use App\Nova\NewReview;
use App\Nova\ModaratedReview;
use App\Nova\ApplicationUse;
use App\Nova\PrivacyPolicy;
use App\Nova\TermsOfService;
use App\Nova\Question;
use App\Nova\AboutAnalise;



use Binarcode\NovaChat\Resources\MessagesResource;

use Binarcode\NovaChat\Resources\RecipientResource;

use Laravel\Nova\Tool;



use App\Models\Feedback as ModelRegister;

use App\Nova\CategoryOfAnalysis;
//use App\Nova\ComplexOfAnalyzes;


class NovaServiceProvider extends NovaApplicationServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */

    public function boot()
    {

        parent::boot();

        Nova::withoutNotificationCenter();

        Footer::set('<p class="text-center">ArchimedMedical 2022</p>');
        Nova::style('asd',asset('NovaCss.css'));


        Nova::mainMenu(function () {

            if(auth()->user()->role_id == 1){


                $countNewFedback = ModelRegister::where('status',1)->count();
                return [

//                    MenuItem::externalLink('Сообшение',''),

                    MenuSection::make('Пользователи', [
                        MenuItem::resource(User::class),
                        MenuItem::resource(Admin::class),
//                        MenuItem::resource(Moderators::class),
                    ])->collapsable()->icon('user'),

                    MenuSection::resource(StartInfo::class),
                    MenuSection::resource(UserAddNewCity::class),
                    MenuSection::resource(SearchResult::class),

                    MenuSection::make('Отзывы', [
                        MenuSection::resource(NewReview::class),
                        MenuSection::resource(ModaratedReview::class),
                    ])->collapsable(),

                    MenuSection::make('О преложении', [
                        MenuSection::resource(ApplicationUse::class),
                        MenuSection::resource(PrivacyPolicy::class),
                        MenuSection::resource(TermsOfService::class),
                    ])->collapsable(),
                   // MenuSection::resource(AboutApp::class),

//
                MenuSection::make('Города', [
//                    MenuItem::resource(Survey::class),
//                    MenuItem::resource(SurveysCategories::class),
                ])->collapsable(),


                    MenuSection::make('Обратная связь при регистрации' . '  ' . '  ' .$countNewFedback, [
                        MenuItem::resource(FeedbackRegister::class),
                        MenuItem::resource(OldFeedbackRegister::class),
                    ])->collapsable() ,

                    MenuSection::resource(NewsAndSales::class),
                    MenuSection::resource(InfoArchimedMedical::class),
                    MenuSection::resource(Question::class),
                    MenuSection::resource(AboutAnalise::class),
                ];
            }if(auth()->user()->role_id == 2){

                return [


//                    MenuSection::resource(User::class)->icon('user'),
//
//                    MenuSection::make('Пользователи', [
//                        MenuItem::resource(User::class),
//                    ])->collapsable()->icon('user'),
//
//                    MenuSection::resource(StartInfo::class),
//                    MenuSection::resource(UserAddNewCity::class),
//                    MenuSection::resource(SearchResult::class),
//

//
//                MenuSection::make('Обследование', [
//                    MenuItem::resource(Survey::class),
//                    MenuItem::resource(SurveysCategories::class),
//                ])->collapsable(),

                    MenuSection::make('Обратная связь', [
                        MenuItem::resource(FeedbackRegister::class),
                        MenuItem::resource(OldFeedbackRegister::class),
                    ])->collapsable(),
//                    MenuSection::resource(NewsAndSales::class),
//                    MenuSection::resource(InfoArchimedMedical::class),
                ];
            }




        });

    }

    /**
     * Register the Nova routes.
     *
     * @return void
     */
    protected function routes()
    {
        Nova::routes()
                ->withAuthenticationRoutes()
                ->withPasswordResetRoutes()
                ->register();
    }

    /**
     * Register the Nova gate.
     *
     * This gate determines who can access Nova in non-local environments.
     *
     * @return void
     */

    protected function gate()
    {

        Gate::define('viewNova', function ($user) {
            return in_array($user->email, [
                //
            ]);
        });
    }

    /**
     * Get the dashboards that should be listed in the Nova sidebar.
     *
     * @return array
     */
    protected function dashboards()
    {
        return [
            new \App\Nova\Dashboards\Main,
        ];
    }



    /**
     * Get the tools that should be listed in the Nova sidebar.
     *
     * @return array
     */
    public function tools()
    {
        return [

        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {


    }
}
