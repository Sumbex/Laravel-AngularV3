import { Component, OnInit } from '@angular/core';
import { AcuerdoAsambleaService } from 'src/app/servicios/acuerdo-asamblea.service';
import { Acuerdo } from 'src/app/modelos/acuerdo.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-acuerdos-socio',
  templateUrl: './acuerdos-socio.component.html',
  styleUrls: ['./acuerdos-socio.component.css']
})
export class AcuerdosSocioComponent implements OnInit {

  //Tabla de Acuerdos
  acuerdos: Acuerdo[] = [];

  acuerdo: Acuerdo = new Acuerdo();

  modal;

  // VARIABLES PARA SELECT ANIO
  selectAnio;
  idAnioActual;
  textSelectAnio: string;

  constructor(
    public _acuerdosService: AcuerdoAsambleaService,
    public _fechasService: PortalSociosService,
    config: NgbModalConfig,
    private modalService: NgbModal
  ) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    // CARGAR AÑOS
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
    this.cargarFechasActuales();
  }

  cargarFechasActuales() {
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
        this.textSelectAnio = response.descripcion;
        this.getAcuerdos();
      },
      error => {
        console.log(error);
      }
    )
  }

  changeAnio(valorSelect) {
    this.textSelectAnio = valorSelect.target.selectedOptions[0].text;
    this.idAnioActual = valorSelect.target.value;
    this.getAcuerdos();
  }

  abrirModalTest(modalMenutest, acuerdoId: string) {
    this.acuerdo = new Acuerdo();
    this.modal = this.modalService.open(modalMenutest, { size: 'xl' });
    this._acuerdosService.getAcuerdoPorId(acuerdoId).subscribe(response => {
      this.acuerdo = response.acuerdo;
    }, error => {
      console.log(error);
    });
  }

  getAcuerdos() {
    this.acuerdos = [];
    this._acuerdosService.getAcuerdosAsamblea(this.textSelectAnio).subscribe(response => {
      if (response.estado == 'success') {
        this.acuerdos = response.acuerdos;
      } else {
        // alert(response.mensaje);
      }
    }, error => {
      console.log(error);
    });
  }

}
