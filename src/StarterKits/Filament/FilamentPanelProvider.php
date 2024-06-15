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
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class FilamentPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id(config('rest-presenter.panel.path'))
            ->path(config('rest-presenter.panel.path'))
            ->font('Work Sans')
            ->brandName(config('rest-presenter.panel.brand_name'))
            ->brandLogo(
                fn () => config('rest-presenter.panel.brand_logo')
                    ? view('rest-presenter::brand-logo')
                    : null,
            )
            ->maxContentWidth(config('rest-presenter.panel.max_width'))
            ->topNavigation(config('rest-presenter.panel.top_navigation'))
            ->globalSearch(false)
            ->spa()
            ->discoverResources(in: __DIR__.'/Resources', for: 'XtendPackages\\RESTPresenter\\StarterKits\\Filament\\Resources')
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
            ->filter(fn (Panel $panel): bool => $panel->getId() !== config('rest-presenter.panel.path'))
            ->keys();

        $panelIds->each(
            fn (string $panelId) => $this->setUserMenuPanelRoute($panelId),
        );
    }

    private function setUserMenuPanelRoute(string $panelId): void
    {
        Filament::getPanel($panelId)->userMenuItems([
            MenuItem::make()
                ->label(config('rest-presenter.panel.brand_name'))
                ->url(fn (): string => '/'.config('rest-presenter.panel.path'))
                ->icon('heroicon-o-server-stack'),
        ]);
    }
}
