<?php

namespace App\Providers;

use App\Interfaces\CountryRepositoryInterface;
use App\Interfaces\CityRepositoryInterface;
use App\Interfaces\RegionRepositoryInterface;
use App\Interfaces\SubRegionRepositoryInterface;
use App\Interfaces\LanguageRepositoryInterface;
use App\Interfaces\ActivityRepositoryInterface;
use App\Interfaces\UserInterface;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\VendorInterface;
use App\Interfaces\LawyerRepositoryInterface;
use App\Interfaces\CustomerRepositoryInterface;
use App\Interfaces\NewsRepositoryInterface;
use App\Interfaces\SectionOfLawRepositoryInterface;
use App\Interfaces\InstructionRepositoryInterface;
use App\Interfaces\BranchOfLawRepositoryInterface;
use App\Interfaces\RegisterGradeInterface;
use App\Interfaces\SubscriptionRepositoryInterface;
use App\Interfaces\AppointmentRepositoryInterface;
use App\Interfaces\ReservationRepositoryInterface;
use App\Repositories\CountryRepository;
use App\Repositories\CityRepository;
use App\Repositories\RegionRepository;
use App\Repositories\SubRegionRepository;
use App\Repositories\LanguageRepository;
use App\Repositories\ActivityRepository;
use App\Repositories\UserRepository;
use App\Repositories\RoleRepository;
use App\Repositories\VendorRepository;
use App\Repositories\LawyerRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\NewsRepository;
use App\Repositories\SectionOfLawRepository;
use App\Repositories\InstructionRepository;
use App\Repositories\BranchOfLawRepository;
use App\Repositories\RegisterGradeRepository;
use App\Repositories\SubscriptionRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\ReservationRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(CountryRepositoryInterface::class, CountryRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(RegionRepositoryInterface::class, RegionRepository::class);
        $this->app->bind(SubRegionRepositoryInterface::class, SubRegionRepository::class);
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
        $this->app->bind(LanguageRepositoryInterface::class, LanguageRepository::class);
        $this->app->bind(VendorInterface::class, VendorRepository::class);
        $this->app->bind(LawyerRepositoryInterface::class, LawyerRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class, CustomerRepository::class);
        $this->app->bind(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->bind(SectionOfLawRepositoryInterface::class, SectionOfLawRepository::class);
        $this->app->bind(InstructionRepositoryInterface::class, InstructionRepository::class);
        $this->app->bind(BranchOfLawRepositoryInterface::class, BranchOfLawRepository::class);
        $this->app->bind(RegisterGradeInterface::class, RegisterGradeRepository::class);
        $this->app->bind(SubscriptionRepositoryInterface::class, SubscriptionRepository::class);
        $this->app->bind(AppointmentRepositoryInterface::class, AppointmentRepository::class);
        $this->app->bind(ReservationRepositoryInterface::class, ReservationRepository::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
