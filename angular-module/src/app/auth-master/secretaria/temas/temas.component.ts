import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { VotacionesBryanmService } from 'src/app/votaciones-bryanm.service';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-temas',
  templateUrl: './temas.component.html',
  styleUrls: ['./temas.component.css']
})
export class TemasComponent implements OnInit {

  remitente;
  ingresandoTema = false;
  actualizandoTema = false;
  terminarTema = false;
  cancelarTema = false;

  idAnioActual;
  idMesActual;
  idTipoActual;

  selectAnio;
  selectMes;
  selectTipo;

  cargarSelect = 0;

  datosTema = {
    fecha: '',
    titulo: '',
    descripcion: ''
  };

  datosActTemas = {
    id: '',
    fecha: '',
    titulo: '',
    descripcion: ''
  };

  temasActivos = [];
  temasHistorial = [];

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _votaciones: VotacionesBryanmService, private _fechasService: AniosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  abrirModalIngreso(modal) {
    this.modalService.open(modal, { size: 'xl' });
    this.cargarDatos();
  }

  abrirModalActivos(modal) {
    this.temasActivos = [];
    this.modalService.open(modal, { size: 'xl' });
    this.traerTemasActivos();
  }

  abrirModalHistorial(modal) {
    this.modalService.open(modal, { size: 'xl' });
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
    this.cargarSelectHistorial();
  }

  abrirModalActualizar(modal, tema) {
    this.cargarDatosActualizar(tema);
    this.modalService.open(modal, { size: 'xl' });
  }

  cargarDatosActualizar(tema) {
    this.datosActTemas.id = tema.id;
    this.remitente = tema.nombre;
    this.datosActTemas.fecha = tema.fecha_inicio;
    this.datosActTemas.titulo = tema.titulo;
    this.datosActTemas.descripcion = tema.descripcion;
  }

  changeAnio(valorSelect) {
    this.idAnioActual = valorSelect.target.value;
    this.traerHistorial();
  }

  changeMes(valorSelect) {
    this.idMesActual = valorSelect.target.value;
    this.traerHistorial();
  }

  changeTipo(valorSelect) {
    this.idTipoActual = valorSelect.target.value;
    this.traerHistorial();
  }

  cargarSelectHistorial() {
    this.cargarSelect = 0;
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
        this.cargarSelect++;
        if (this.cargarSelect == 3) {
          this.traerHistorial();
        }
      },
      error => {
        console.log(error);
      }
    )
    //Cargar id del Mes actual
    this._fechasService.getMesActual().subscribe(
      response => {
        this.idMesActual = response.id;
        this.cargarSelect++;
        if (this.cargarSelect == 3) {
          this.traerHistorial();
        }
      },
      error => {
        console.log(error);
      }
    )
    //Cargar Tipos
    this._votaciones.traerTipos().subscribe(
      res => {
        this.selectTipo = res.tipos;
        this.cargarSelect++;
        if (this.cargarSelect == 3) {
          this.idTipoActual = 0;
          this.traerHistorial();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  cargarDatos() {
    this._votaciones.traerDatos().subscribe(
      res => {
        if (res.estado == 'success') {
          this.remitente = res.datos.nombre;
          this.datosTema.fecha = res.datos.fecha;
        } else {
          alert(res.mensaje);
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  limpiarTema() {
    this.datosTema.titulo = '';
    this.datosTema.descripcion = '';
  }

  ingresarTema() {
    this.ingresandoTema = true;
    this._votaciones.ingresarTema(this.datosTema).subscribe(
      res => {
        if (res.estado == 'success') {
          this.limpiarTema();
          this.ingresandoTema = false;
          alert(res.mensaje);
        } else {
          this.ingresandoTema = false;
          alert(res.mensaje);
        }
      }, error => {
        this.ingresandoTema = false;
        console.log(error);
      }
    )
  }

  actualizarTema() {
    this.actualizandoTema = true;
    this._votaciones.actualizarTema(this.datosActTemas).subscribe(
      res => {
        if (res.estado = 'success') {
          this.traerTemasActivos();
          this.actualizandoTema = false;
          alert(res.mensaje);
        } else {
          this.actualizandoTema = false;
          alert(res.mensaje);
        }
      }, error => {
        this.actualizandoTema = false;
        console.log(error);
      }
    )
  }

  cancelarTemas() {
    this.cancelarTema = true;
    this._votaciones.cancelarTema(this.datosActTemas.id).subscribe(
      res => {
        if (res.estado = 'success') {
          this.traerTemasActivos();
          this.cancelarTema = false;
          alert(res.mensaje);
          document.getElementById("cerrarModalEditar").click();
        } else {
          this.cancelarTema = false;
          alert(res.mensaje);
        }
      }, error => {
        this.cancelarTema = false;
        console.log(error);
      }
    )
  }

  traerTemasActivos() {
    this._votaciones.traerTemasActivos().subscribe(
      res => {
        if (res.estado == 'success') {
          this.temasActivos = res.temas;
        } else {
          alert(res.mensaje);
          document.getElementById("cerrarActivos").click();
        }
      }, error => {
        console.log(error);
      }
    )
  }

  traerHistorial() {
    this.temasHistorial = [];
    this._votaciones.traerHistorialTemas(this.idAnioActual, this.idMesActual, this.idTipoActual).subscribe(
      res => {
        if (res.estado == 'success') {
          this.temasHistorial = res.temas;
        } else {
          alert(res.mensaje);
        }
      },
      error => {
        console.log(error);
      }
    )
  }

}
