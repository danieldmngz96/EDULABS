import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { HttpClient } from '@angular/common/http';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private tokenKey = 'auth_token';
  private userKey = 'auth_user';
  private apiUrl = environment.apiUrl;
  private isBrowser = typeof window !== 'undefined';

  constructor(private router: Router, private http: HttpClient) {}

  // ✅ Verifica si el usuario tiene un token válido
  isLoggedIn(): boolean {
    const token = localStorage.getItem('token');
    return !!token; // true si hay token, false si no
  }

  // ✅ Guarda el token al iniciar sesión
  login(credentials: { email: string; password: string }) {
    return this.http.post(`${this.apiUrl}/login`, credentials);
  }

  // ✅ Elimina el token al cerrar sesión
  logout() {
    localStorage.removeItem('token');
  }

  // ✅ Obtiene el token actual (opcional)
  getToken(): string | null {
    return localStorage.getItem('token');
  }

  // getUser() {
  //   const user = localStorage.getItem(this.userKey);
  //   return user ? JSON.parse(user) : null;
  // }
  getUser() {
    if (this.isBrowser) {
      const user = localStorage.getItem('user');
      return user ? JSON.parse(user) : null;
    }
    return null;
  }
}
