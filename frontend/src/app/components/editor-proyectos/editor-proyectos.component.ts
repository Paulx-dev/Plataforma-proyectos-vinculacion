import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { ProyectoService } from '../../services/proyecto.service';
import { Proyecto } from '../../models/proyecto.model';

@Component({
  selector: 'app-editor-proyectos',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './editor-proyectos.component.html',
  styleUrl: './editor-proyectos.component.css'
})
export class EditorProyectosComponent implements OnInit {

  proyectos: Proyecto[] = [];
  filtroCarrera = '';

  modalAbierto = false;
  proyectoEditando: Proyecto | null = null;

  editNombre = '';
  editDescripcion = '';
  editTutor = '';
  editTutorBloqueado = true;

  private _origNombre = '';
  private _origDescripcion = '';
  private _origTutor = '';

  tutoresDisponibles = ['Ing. Juan Pérez', 'Ing. María López'];

  constructor(
    private proyectoService: ProyectoService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.cargarProyectos();
  }

  cargarProyectos(): void {
    this.proyectoService.getProyectos().subscribe({
      next: (data) => {
        this.proyectos = data;
        this.cdr.detectChanges();
      },
      error: (err) => console.error('Error al cargar proyectos:', err)
    });
  }

  get proyectosFiltrados(): Proyecto[] {
    if (!this.filtroCarrera) return this.proyectos;
    return this.proyectos.filter(p => p.carrera === this.filtroCarrera);
  }

  confirmarEliminar(p: Proyecto): void {
    if (confirm(`¿Estás seguro de que quieres eliminar el proyecto "${p.nombre}"?`)) {
      this.proyectoService.eliminarProyecto(p.id).subscribe({
        next: () => this.cargarProyectos(),
        error: (err) => console.error('Error al eliminar:', err)
      });
    }
  }

  abrirEditor(p: Proyecto): void {
    this.proyectoEditando = p;
    this.editNombre = p.nombre;
    this.editDescripcion = p.descripcion;
    this.editTutor = p.tutor;
    this.editTutorBloqueado = true;
    this._origNombre = p.nombre;
    this._origDescripcion = p.descripcion;
    this._origTutor = p.tutor;
    this.modalAbierto = true;
  }

  cerrarModal(): void {
    this.modalAbierto = false;
    this.proyectoEditando = null;
  }

  revertirCambios(): void {
    if (confirm('¿Estás seguro de que quieres cancelar? Se perderán los cambios realizados.')) {
      this.editNombre = this._origNombre;
      this.editDescripcion = this._origDescripcion;
      this.editTutor = this._origTutor;
      this.editTutorBloqueado = true;
    }
  }

  habilitarTutor(): void {
    this.editTutorBloqueado = false;
  }

  guardarCambios(): void {
    if (!this.proyectoEditando) return;

    const datos: Partial<Proyecto> = {
      nombre: this.editNombre,
      descripcion: this.editDescripcion,
      tutor: this.editTutor
    };

    this.proyectoService.actualizarProyecto(this.proyectoEditando.id, datos).subscribe({
      next: () => {
        this.cerrarModal();
        this.cargarProyectos();
      },
      error: (err) => console.error('Error al actualizar:', err)
    });
  }

  get cuposRestantes(): number {
    if (!this.proyectoEditando) return 0;
    return this.proyectoEditando.cupos_max - this.proyectoEditando.cupos_usados;
  }
}