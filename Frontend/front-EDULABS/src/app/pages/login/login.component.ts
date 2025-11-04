import { Component } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from './../../core/services/auth.service';
import { HttpClient, HttpClientModule } from '@angular/common/http';

@Component({
  selector: 'app-login',
  standalone: true,
  imports: [CommonModule, FormsModule, HttpClientModule],
  templateUrl: './login.component.html',
})
export class LoginComponent {
  email = '';
  password = '';
  errorMessage = '';
  showPassword = false;

  constructor(
    private http: HttpClient,
    private authService: AuthService,
    private router: Router
  ) {}

  login() {
    (async () => {
      try {
        // 1️⃣ Obtener la cookie CSRF
        await fetch('http://localhost:8000/sanctum/csrf-cookie', {
          credentials: 'include',
        });

        // 2️⃣ Hacer login con email y password
        const res = await fetch('http://localhost:8000/api/login', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          credentials: 'include',
          body: JSON.stringify({
            email: this.email,
            password: this.password,
          }),
        });

        if (!res.ok)
          throw new Error('Credenciales incorrectas o error en el servidor');

        const data = await res.json();

        // 3️⃣ Guardar token y usuario en localStorage
        localStorage.setItem('token', data.token);
        localStorage.setItem('user', JSON.stringify(data.user));

        console.log('Redirigiendo al dashboard...');
        this.router.navigate(['/dashboard']);
      } catch (err) {
        console.error(err);
        this.errorMessage = 'Credenciales incorrectas o error en el servidor.';
      }
    })();
  }

  // goToDashboard(){
  //   this.router.navigateByUrl('/dashboard');
  // }
}
