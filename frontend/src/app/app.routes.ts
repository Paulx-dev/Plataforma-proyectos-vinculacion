import { Routes } from '@angular/router';
import { LayoutComponent } from './components/layout/layout.component';
import { ListaProyectosComponent } from './components/lista-proyectos/lista-proyectos.component';
import { FormularioProyectoComponent } from './components/formulario-proyecto/formulario-proyecto.component';
import { EditorProyectosComponent } from './components/editor-proyectos/editor-proyectos.component';
import { SolicitudesComponent } from './components/solicitudes/solicitudes.component';

export const routes: Routes = [
  // Todas las rutas viven dentro del Layout (sidebar + contenido)
  {
    path: '',
    component: LayoutComponent,
    children: [
      { path: '',             component: ListaProyectosComponent },
      { path: 'nuevo',        component: FormularioProyectoComponent },
      { path: 'editor',       component: EditorProyectosComponent },
      { path: 'solicitudes',  component: SolicitudesComponent }
    ]
  }
];
