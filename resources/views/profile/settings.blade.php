@extends('layouts.master')

@section('content')
<div class="container py-4">
    <h2 class="page-title mb-4">Profile Settings</h2>
    <div class="row">
        <div class="col-lg-8 mx-auto">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                <div class="card mb-4">
                    <div class="card-body">
                        @livewire('profile.update-profile-information-form')
                    </div>
                </div>
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="card mb-4">
                    <div class="card-body">
                        @livewire('profile.update-password-form')
                    </div>
                </div>
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="card mb-4">
                    <div class="card-body">
                        @livewire('profile.two-factor-authentication-form')
                    </div>
                </div>
            @endif

            <div class="card mb-4">
                <div class="card-body">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                <div class="card mb-4">
                    <div class="card-body">
                        @livewire('profile.delete-user-form')
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

