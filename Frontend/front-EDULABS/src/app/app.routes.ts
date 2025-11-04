import { Routes } from '@angular/router';
import { LoginComponent } from './pages/login/login.component';

export const routes: Routes = [
  { path: '', component: LoginComponent },
  {
    path: 'dashboard',
    loadComponent: () =>
      import('./pages/admin/admin-dashboard/admin-dashboard.component').then(
        (m) => m.AdminDashboardComponent
      ),
    children: [
      {
        path: 'user',
        loadComponent: () =>
          import('./pages/admin/user-panel/user-panel.component').then(
            (m) => m.UserPanelComponent
          ),
      },
      {
        path: 'admin',
        loadComponent: () =>
          import('./pages/admin/admin-panel/admin-panel.component').then(
            (m) => m.AdminPanelComponent
          ),
      },
      { path: '', redirectTo: 'user', pathMatch: 'full' },
    ],
  },
  { path: '**', redirectTo: '' },
];
