import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AniosService } from 'src/app/servicios/anios.service';
import { ArchivadorBienestarService } from '../../../servicios/archivador-bienestar.service';

@Component({
  selector: 'app-archivador-bienestar',
  templateUrl: './archivador-bienestar.component.html',
  styleUrls: ['./archivador-bienestar.component.css']
})
export class ArchivadorBienestarComponent implements OnInit {

  url
  botonIngresar = false;

  remitente = ''
  selectAnio
  selectMes
  idAnioActualN
  idMesActualN
  idAnioActualA
  idMesActualA
  cargarDatos = 0

  selectTipo

  ingresandoNota = false;
  ingresandoArchivo = false;

  notas = []
  archivos = []

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

  edicionDocumento = false;
  edicionSelect = false;
  edicionTexto = false;
  nuevoIngreso = false;
  varType;

  datosEdicion = {
    id: '',
    campo: '',
    input: ''
  }

  valorInput = '';
  edicionArchivo;
  selectEdicionTipo = 0;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _archivadorService: ArchivadorBienestarService, private _fechasService: AniosService) { 
    config.backdrop = 'static';
    config.keyboard = false;
  }

  openModal(content) {
    this.modalService.open(content, { size: 'xl' });
  }

  ngOnInit() {
    this.url = document.location.origin + '/';
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
  }

  ingresarNota(){

  }
}
