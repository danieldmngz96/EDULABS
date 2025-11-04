import { Component, signal, WritableSignal } from '@angular/core';
import { CommonModule } from '@angular/common'; // <- IMPORTANTE
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-admin-panel',
  standalone: true,
  imports: [CommonModule, FormsModule], // <- Asegúrate de importar CommonModule
  templateUrl: './admin-panel.component.html',
  styleUrls: ['./admin-panel.component.scss'],
})
export class AdminPanelComponent {
  // MODELOS
  usuariosDisponibles = signal<Usuario[]>([
    { id: 1, nombre: 'Aura' },
    { id: 2, nombre: 'Daniel' },
    { id: 3, nombre: 'Carlos' },
  ]);

  grupos = signal<Grupo[]>([
    { id: 1, nombre: 'Marketing', usuarios: [] },
    { id: 2, nombre: 'Desarrolladores', usuarios: [] },
  ]);

  // Signals para formularios
  nuevoGrupoNombre = signal('');
  grupoSeleccionado: WritableSignal<Grupo | null> = signal(null);
  usuarioSeleccionado: WritableSignal<Usuario | null> = signal(null);

  crearGrupo() {
    const nombre = this.nuevoGrupoNombre();
    if (!nombre.trim()) return;

    const nuevoGrupo: Grupo = {
      id: this.grupos().length + 1,
      nombre,
      usuarios: [],
    };

    this.grupos.set([...this.grupos(), nuevoGrupo]);
    this.nuevoGrupoNombre.set('');
  }

  asignarUsuario() {
    const grupo = this.grupoSeleccionado();
    const usuario = this.usuarioSeleccionado();

    if (grupo && usuario) {
      if (!grupo.usuarios.find((u) => u.id === usuario.id)) {
        grupo.usuarios.push(usuario);
        this.grupos.set([...this.grupos()]);
      }
    }
  }
}

// MODELOS
interface Usuario {
  id: number;
  nombre: string;
}

interface Grupo {
  id: number;
  nombre: string;
  usuarios: Usuario[];
}
