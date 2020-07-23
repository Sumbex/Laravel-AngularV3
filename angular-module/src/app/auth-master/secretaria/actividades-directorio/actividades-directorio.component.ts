import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Actividades } from 'src/app/modelos/actividadesDirecctorio.model';
import { ActividadesDirectorioService } from 'src/app/servicios/actividades-directorio.service';
import { VotacionesBryanmService } from 'src/app/votaciones-bryanm.service';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-actividades-directorio',
  templateUrl: './actividades-directorio.component.html',
  styleUrls: ['./actividades-directorio.component.css']
})
export class ActividadesDirectorioComponent implements OnInit {

  cargarSelect: number = 0;

  anioId;
  selectAnios: Anios[];

  mostrar: boolean = false;

  filtroId = 0;
  selectFiltro = [];

  mesId;
  selectMeses: Meses[];

  ingresandoAct: boolean = false;
  cargandoTabla: boolean = false;

  actividad: Actividades = new Actividades;
  actividades: Actividades[];
  actividadesFiltro: Actividades[];

  constructor(config: NgbModalConfig, private modalService: NgbModal, public _actividad: ActividadesDirectorioService, public _votaciones: VotacionesBryanmService, public _fechasService: AniosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {

  }

  mostarTexto() {
    this.mostrar = !this.mostrar;
  }

  abrirModalIngreso(modal) {
    this.cargarFecha();
    this.modalService.open(modal, { size: 'xl' });
  }

  abrirModalActividades(modal) {
    this.selectAnios = JSON.parse(localStorage.getItem('anios'));
    this.selectMeses = JSON.parse(localStorage.getItem('meses'));
    this.cargarSelectActividades();
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

  changeAnio(valorSelect) {
    this.anioId = valorSelect.target.value;
    this.traerActividades();
  }

  changeMes(valorSelect) {
    this.mesId = valorSelect.target.value;
    this.traerActividades();
  }

  changeFiltro(valorSelect) {
    this.filtroId = valorSelect.target.value;
    this.filtrarAct(this.filtroId);
  }

  cargarSelectActividades() {
    this.cargarSelect = 0;
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.anioId = response.id;
        this.cargarSelect++;
        if (this.cargarSelect == 3) {
          this.traerActividades();
        }
      },
      error => {
        console.log(error);
      }
    )
    //Cargar id del Mes actual
    this._fechasService.getMesActual().subscribe(
      response => {
        this.mesId = response.id;
        this.cargarSelect++;
        if (this.cargarSelect == 3) {
          this.traerActividades();
        }
      },
      error => {
        console.log(error);
      }
    )

    //Cargar id del Filtro actual
    this._actividad.traerTiposAct().subscribe(
      response => {
        this.selectFiltro = response.tipos;
        this.filtroId = 0;
        this.cargarSelect++;
        if (this.cargarSelect == 3) {
          this.traerActividades();
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

  filtrarAct(filtroId) {
    if (filtroId == 0) {
      this.actividadesFiltro = this.actividades;
    } else {
      this.actividadesFiltro = this.actividades.filter(filtro => filtro.estado_actividad_id == filtroId);
    }
  }

  traerActividades() {
    this.actividadesFiltro = [];
    this.actividades = [];
    this.filtroId = 0;
    this.cargandoTabla = true;
    this._actividad.traerActividades(this.anioId, this.mesId).subscribe(
      res => {
        if (res.estado == 'success') {
          this.cargandoTabla = false;
          this.actividades = res.actividades;
          this.actividadesFiltro = res.actividades;
        } else {
          this.cargandoTabla = false;
          alert(res.mensaje);
        }
      }, error => {
        this.cargandoTabla = false;
        console.log(error);
      }
    )
  }
}
