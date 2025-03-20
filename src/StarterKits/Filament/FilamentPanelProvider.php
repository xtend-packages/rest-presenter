<?php

declare(strict_types=1);

namespace XtendPackages\RESTPresenter\StarterKits\Filament;

use Filament\Facades\Filament;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use XtendPackages\RESTPresenter\Concerns\InteractsWithConfig;

final class FilamentPanelProvider extends PanelProvider
{
    use InteractsWithConfig;

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id($this->config('rest-presenter.panel.path'))
            ->path($this->config('rest-presenter.panel.path'))
            ->font('Work Sans')
            ->brandName($this->config('rest-presenter.panel.brand_name'))
            ->brandLogo(
                fn () => $this->config('rest-presenter.panel.brand_logo')
                    ? view('rest-presenter::brand-logo')
                    : null,
            )
            ->maxContentWidth($this->config('rest-presenter.panel.max_width'))
            ->topNavigation($this->config('rest-presenter.panel.top_navigation'))
            ->globalSearch(false)
            ->darkMode(isForced: true)
            ->spa()
            ->discoverResources(in: __DIR__.'/Resources', for: 'XtendPackages\\RESTPresenter\\StarterKits\\Filament\\Resources')
            ->discoverPages(in: __DIR__.'/Pages', for: 'XtendPackages\\RESTPresenter\\StarterKits\\Filament\\Pages')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        $panelIds = collect(Filament::getPanels())
            ->filter(fn (Panel $panel): bool => $panel->getId() !== $this->config('rest-presenter.panel.path'))
            ->keys();

        $panelIds->each(
            fn (string $panelId) => $this->setUserMenuPanelRoute($panelId),
        );
    }

    private function setUserMenuPanelRoute(string $panelId): void
    {
        Filament::getPanel($panelId)->userMenuItems([
            MenuItem::make()
                ->visible(fn (): bool => $this->getAuthenticatedUser())
                ->label($this->config('rest-presenter.panel.brand_name'))
                ->url(fn (): string => '/'.$this->config('rest-presenter.panel.path'))
                ->icon('heroicon-o-server-stack'),
        ]);
    }

    private function getAuthenticatedUser(): bool
    {
        $user = type(auth()->user())->as(Authenticatable::class);

        if (! method_exists($user, 'canAccessPanel')) {
            return false;
        }

        /** @var \Filament\Models\Contracts\FilamentUser $user */
        return $user->canAccessPanel(
            Filament::getPanel($this->config('rest-presenter.panel.path')),
        );
    }
}
