import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ProyectoService } from '../../services/proyecto.service';
import { Solicitud } from '../../models/proyecto.model';

@Component({
  selector: 'app-solicitudes',
  standalone: true,
  imports: [CommonModule],
  templateUrl: './solicitudes.component.html',
  styleUrl: './solicitudes.component.css'
})
export class SolicitudesComponent implements OnInit {

  solicitudes: Solicitud[] = [];
  cargando = true;

  constructor(
    private proyectoService: ProyectoService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.cargar();
  }

  cargar(): void {
    this.cargando = true;
    this.proyectoService.getSolicitudes().subscribe({
      next: (data) => {
        this.solicitudes = data;
        this.cargando = false;
        this.cdr.detectChanges();
      },
      error: (err) => {
        console.error('Error al cargar solicitudes:', err);
        this.cargando = false;
        this.cdr.detectChanges();
      }
    });
  }

  get pendientes(): Solicitud[] {
    return this.solicitudes.filter(s => s.estado === 'pendiente');
  }

  get procesadas(): Solicitud[] {
    return this.solicitudes.filter(s => s.estado !== 'pendiente');
  }

  confirmarAccion(accion: 'aceptar' | 'denegar', s: Solicitud): void {
    const mensaje = accion === 'aceptar'
      ? `¿Estás seguro de que quieres aceptar la solicitud de ${s.estudiante} para el proyecto "${s.nombre_proyecto}"?`
      : `¿Estás seguro de que quieres denegar la solicitud de ${s.estudiante} para el proyecto "${s.nombre_proyecto}"?`;

    if (!confirm(mensaje)) return;

    const obs = accion === 'aceptar'
      ? this.proyectoService.aceptarSolicitud(s.id)
      : this.proyectoService.denegarSolicitud(s.id);

    obs.subscribe({
      next: () => this.cargar(),
      error: (err) => console.error(`Error al ${accion}:`, err)
    });
  }

  resetearSolicitudes(): void {
    if (!confirm('¿Resetear solicitudes a estado pendiente?')) return;
    this.proyectoService.resetSolicitudes().subscribe({
      next: () => this.cargar(),
      error: (err) => console.error('Error al resetear:', err)
    });
  }
}