import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { DirectorioService } from 'src/app/servicios/directorio.service';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-directorio-socio',
  templateUrl: './directorio-socio.component.html',
  styleUrls: ['./directorio-socio.component.css']
})
export class DirectorioSocioComponent implements OnInit {

  datosDirectorio = {
    fecha: '',
    sec_tipo_reunion_id: '',
    titulo: '',
    presidente: false,
    tesorero: false,
    secretario: false,
    primerDirector: false,
    segundoDirector: false,
    asistencia: '',
    temas: '',
    acuerdos: ''
  };

  //loading
  ingresandoDatos = false;

  //listar
  listarDirectorio;
  listarDirectorioDetalle;

  //variable para asociar al modal
  modalHistorial;
  modalDetalle;

  //filtra por año
  selectAnio;
  idAnioActual;
  textSelectAnio: string;

  constructor(
    config: NgbModalConfig, 
    private modalService: NgbModal, 
    private _directorio: DirectorioService,
    private _fechasService: AniosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }
  ngOnInit() {
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
  }

  abrirModal(modalDirectiva) {
    this.modalHistorial = this.modalService.open(modalDirectiva, { size: 'xl' });
    this.cargarFechasActuales();
  }
  abrirModalDetalle(modalDetalle, id) {
    this.traerDetalle(id);
    this.modalDetalle = this.modalService.open(modalDetalle, { size: 'xl' });
  }

  cargarFechasActuales() {
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
        this.textSelectAnio = response.descripcion;
        this.visualizarDirectorio();
      },
      error => {
        console.log(error);
      }
    )
  }

  changeAnio(valorSelect){
    this.textSelectAnio = valorSelect.target.selectedOptions[0].text;
    this.idAnioActual = valorSelect.target.value;
    this.visualizarDirectorio();
  }

  visualizarDirectorio() {
    this._directorio.visualizarDirectorio(this.textSelectAnio).subscribe(response => {
      if (response.estado == 'failed') {
        alert(response.mensaje);
        this.modalHistorial.close();
      } else {
        this.listarDirectorio = response.listar;
        console.log(this.listarDirectorio);

      }
    }, error => {
      console.log(error);
    });
  }

  traerDetalle(id) {
    this._directorio.visualizarDirectorioDetalle(id).subscribe(response => {
      if (response.estado == 'failed') {
        alert(response.mensaje);
      } else {
        this.listarDirectorioDetalle = response.listar;
        console.log(this.listarDirectorioDetalle);


      }
    }, error => {
      console.log(error);
    });
  }

}
