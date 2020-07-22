import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Actividades } from 'src/app/modelos/actividadesDirecctorio.model';
import { ActividadesDirectorioService } from 'src/app/servicios/actividades-directorio.service';
import { VotacionesBryanmService } from 'src/app/votaciones-bryanm.service';

@Component({
  selector: 'app-actividades-directorio',
  templateUrl: './actividades-directorio.component.html',
  styleUrls: ['./actividades-directorio.component.css']
})
export class ActividadesDirectorioComponent implements OnInit {

  ingresandoAct: boolean = false;

  actividad: Actividades = new Actividades;

  actividades: Actividades[];

  constructor(config: NgbModalConfig, private modalService: NgbModal, public _actividad: ActividadesDirectorioService, public _votaciones: VotacionesBryanmService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  abrirModalIngreso(modal) {
    this.cargarFecha();
    this.modalService.open(modal, { size: 'xl' });
  }

  abrirModalActivos(modal) {
    this.traerActividadesActivas();
    this.modalService.open(modal, { size: 'xl' });
  }

  cargarFecha() {
    this._votaciones.traerDatos().subscribe(
      res => {
        if (res.estado == 'success') {
          this.actividad.fecha = res.datos.fecha;
        } else {
          console.log(res.mensaje);
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  limpiarActividad() {
    this.actividad.fecha = '';
    this.actividad.responsable = '';
    this.actividad.actividad = '';
  }

  ingresarActividad() {
    this.ingresandoAct = true;
    this._actividad.ingresarActividad(this.actividad).subscribe(
      res => {
        if (res.estado == 'success') {
          this.ingresandoAct = false;
          this.limpiarActividad();
          alert(res.mensaje)
        } else {
          this.ingresandoAct = false;
          alert(res.mensaje);
        }
      }, error => {
        this.ingresandoAct = false;
        console.log(error);
      }
    )
  }

  traerActividadesActivas() {
    this.actividades = [];
    this._actividad.traerActividadesActivas().subscribe(
      res => {
        if (res.estado == 'success') {
          this.actividades = res.activas;
        } else {
          alert(res.mensaje);
        }
      }, error => {
        console.log(error);
      }
    )
  }
}
