import { Component, OnInit, PLATFORM_ID, inject } from '@angular/core';
import { CommonModule, isPlatformBrowser } from '@angular/common';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-user-panel',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './user-panel.component.html',
})
export class UserPanelComponent implements OnInit {
  uploadedFiles: {
    id: number;
    name: string;
    size: number;
    createdAt: string;
  }[] = [];
  selectedFile: File | null = null;
  private platformId = inject(PLATFORM_ID);

  private getAuthHeader() {
    const token = localStorage.getItem('token');
    if (!token) throw new Error('Usuario no logueado');
    return { Authorization: `Bearer ${token}` };
  }

  ngOnInit() {
    if (isPlatformBrowser(this.platformId)) {
      this.loadFiles();
    }
  }

  onFileSelected(event: any) {
    this.selectedFile = event.target.files[0];
  }

  async loadFiles() {
    try {
      const res = await fetch('http://localhost:8000/api/archivos', {
        method: 'GET',
        headers: this.getAuthHeader(),
      });

      if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);

      const data = await res.json();

      this.uploadedFiles = data.map((f: any) => ({
        id: f.id,
        name: f.nombre_original,
        size: f.tamaño_kb,
        createdAt: f.creado_en,
      }));
    } catch (err) {
      console.error(err);
      Swal.fire('Error', 'No se pudieron cargar los archivos', 'error');
    }
  }

  /**
   * Sube el archivo seleccionado al servidor.
   * Muestra mensajes usando SweetAlert según el resultado.
   * @param event Evento submit del formulario de subida.
   */
  async onUpload(event: Event): Promise<void> {
    event.preventDefault();

    if (!this.selectedFile) {
      await Swal.fire('Error', 'Selecciona un archivo', 'error');
      return;
    }

    // 🔒 Limite de 10 MB
    const MAX_SIZE_BYTES = 10 * 1024 * 1024; // 10 MB
    if (this.selectedFile.size > MAX_SIZE_BYTES) {
      await Swal.fire(
        'Error',
        'El archivo excede el tamaño máximo permitido de 10 MB',
        'error'
      );
      return;
    }

    try {
      // Preparar FormData
      const formData = new FormData();
      formData.append('archivo', this.selectedFile);

      // Obtener token de localStorage
      const token = localStorage.getItem('token');

      // Enviar fetch con token y cookies
      const res = await fetch('http://localhost:8000/api/archivos/subir', {
        method: 'POST',
        credentials: 'include', // cookies Sanctum
        headers: token ? { Authorization: `Bearer ${token}` } : {},
        body: formData,
      });

      if (!res.ok) {
        if (res.status === 401) {
          throw new Error('No autorizado. Tu sesión ha expirado.');
        }
        throw new Error('Error al subir archivo');
      }

      const data = await res.json();

      this.uploadedFiles.push({
        id: data.archivo.id,
        name: data.archivo.nombre_original,
        size: data.archivo.tamaño_kb,
        createdAt: new Date().toISOString(),
      });

      Swal.fire('Éxito', data.mensaje, 'success');
      this.selectedFile = null;
    } catch (err: any) {
      console.error(err);
      Swal.fire(
        'Error',
        err.message || 'No se pudo conectar con el servidor',
        'error'
      );
    }
  }

  async onDelete(fileId: number) {
    const confirmed = await Swal.fire({
      title: 'Eliminar archivo',
      text: '¿Estás seguro de eliminar este archivo?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar',
    });

    if (confirmed.isConfirmed) {
      try {
        const res = await fetch(
          `http://localhost:8000/api/archivos/${fileId}`,
          {
            method: 'DELETE',
            headers: this.getAuthHeader(),
          }
        );

        if (!res.ok) throw new Error('Error al eliminar archivo');

        this.uploadedFiles = this.uploadedFiles.filter((f) => f.id !== fileId);
        Swal.fire('Eliminado', 'Archivo eliminado correctamente', 'success');
      } catch (err) {
        console.error(err);
        Swal.fire('Error', 'No se pudo eliminar el archivo', 'error');
      }
    }
  }
}
