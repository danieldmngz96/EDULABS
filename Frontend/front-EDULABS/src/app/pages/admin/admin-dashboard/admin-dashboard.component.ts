import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Router, RouterOutlet } from '@angular/router';
import { AuthService } from '../../../core/services/auth.service';

@Component({
  selector: 'app-admin-dashboard',
  standalone: true,
  imports: [CommonModule, RouterOutlet],
  templateUrl: './admin-dashboard.component.html',
})
export class AdminDashboardComponent implements OnInit {
  user: any = null;

  constructor(private authService: AuthService, private router: Router) {
    this.user = this.authService.getUser(); // obtiene usuario desde el servicio
  }

  ngOnInit() {
    // Use AuthService which handles browser/platform checks
    this.user = this.authService.getUser();
  }

  logout() {
    this.authService.logout();
    this.router.navigate(['/']);
  }
  goAdmin() {
    this.router.navigate(['./dashboard/admin']);
  }
}
