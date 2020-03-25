import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ArchivadorService } from 'src/app/servicios/archivador.service';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-archivador',
  templateUrl: './archivador.component.html',
  styleUrls: ['./archivador.component.css']
})
export class ArchivadorComponent implements OnInit {

  remitente = ''
  selectAnio
  selectMes
  idAnioActual
  idMesActual
  cargarDatos = 0

  notas = []

  datosNotas = {
    fecha: '',
    nota: ''
  }

  datosArchivos = {
    fecha: '',
    tipo: 0,
    titulo: '',
    archivo: ''
  }

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _archivadorService: ArchivadorService, private _fechasService: AniosService) {
    // customize default values of modals used by this component tree
    config.backdrop = 'static';
    config.keyboard = false;
  }

  open(content) {
    this.modalService.open(content, { size: 'xl' });
    this.cargarFecha();
    this.cargarFechasActuales();
  }

  ngOnInit() {
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
  }

  changeAnio(valorSelect) {
    this.idAnioActual = valorSelect.target.value;
    this.notas = [];
    this.traerNotas();
  }

  changeMes(valorSelect) {
    this.idMesActual = valorSelect.target.value;
    this.notas = [];
    this.traerNotas();
  }

  cargarFecha() {
    this._archivadorService.traerDatos().subscribe(
      res => {
        if (res.estado == 'success') {
          this.remitente = res.datos.nombre;
          this.datosNotas.fecha = res.datos.fecha;
          this.datosArchivos.fecha = res.datos.fecha;
        } else {
          alert(res.mensaje);
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  cargarFechasActuales() {
    this.cargarDatos = 0;
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
        this.cargarDatos++;
        console.log(this.cargarDatos);
        if (this.cargarDatos == 2) {
          this.traerNotas();
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
        this.cargarDatos++;
        console.log(this.cargarDatos);
        if (this.cargarDatos == 2) {
          this.traerNotas();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  ingresarNota() {
    this._archivadorService.ingresarNota(this.datosNotas).subscribe(
      res => {
        if (res.estado = 'success') {
          this.limpiarNotas();
          alert(res.mensaje);
        } else {
          alert(res.mensaje);
        }
      }, error => {
        console.log(error);
      }
    )
  }

  limpiarNotas() {
    this.datosNotas.nota = '';
  }

  traerNotas() {
    this.notas = [];
    this._archivadorService.traerNotas(this.idAnioActual, this.idMesActual).subscribe(
      res => {
        if (res.estado == 'success') {
          this.notas = res.notas;
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
