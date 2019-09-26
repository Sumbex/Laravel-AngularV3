import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { UsuarioService } from 'src/app/servicios/usuarios.service';
import { BryanBienestarService } from 'src/app/servicios/bryans-bienestar.service';

@Component({
  selector: 'app-caja-bienestar-socios',
  templateUrl: './caja-bienestar-socios.component.html',
  styleUrls: ['./caja-bienestar-socios.component.css']
})
export class CajaBienestarSociosComponent implements OnInit {

  //Variables para los select de año y mes
  selectAnio;
  selectMes;

  idAnioActual;
  idMesActual;

  //Variable para las cargas
  cargandoTabla = false;
  cargarDatos = 0;

  //Variable que almacena los valores obtenidos para la tabla caja chica
  datosCajaChica;
  totalesCajaChica;

  //variable para asociar al modal
  modalCajaChica;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService, private _usuariosSevice: UsuarioService, private _bienestarService: BryanBienestarService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
  }

  openModalCajaChica(cajaChicaModal) {
    this.modalCajaChica = this.modalService.open(cajaChicaModal, { size: 'xl' });
    this.limpiarTabla();
    this.cargarFechasActuales();
  }

  //Abrir visor de PDF
  openPDF(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  cargarFechasActuales() {
    this.cargarDatos = 0;
    //Cargar id del Año actual
    this._portalSociosService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
        this.cargarDatos++;
        console.log(this.cargarDatos);
        if(this.cargarDatos == 2){
          this.cargarTablaCajaChica();
        }
      },
      error => {
        console.log(error);
      }
    )
    //Cargar id del Mes actual
    this._portalSociosService.getMesActual().subscribe(
      response => {
        this.idMesActual = response.id;
        this.cargarDatos++;
        console.log(this.cargarDatos);
        if(this.cargarDatos == 2){
          this.cargarTablaCajaChica();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  changeAnio(valorSelect){
    this.limpiarTabla();
    this.idAnioActual = valorSelect.target.value;
    this.recargarTabla();
  }
 
  changeMes(valorSelect){
   this.limpiarTabla();
   this.idMesActual = valorSelect.target.value;
   this.recargarTabla();
  }

  cargarTablaCajaChica(){
    this.cargandoTabla = true;
    this._bienestarService.getCajaChicaBienestarSocios(this.idAnioActual, this.idMesActual).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.cargandoTabla = false;
      }else{
        this.datosCajaChica = response;
        this.totalesCajaChica = response.total;
        this.cargandoTabla = false;
      }
    },
    error => {
      console.log(error);
      this.cargandoTabla = false;
    });
  }

  limpiarTabla(){
    this.datosCajaChica = '';
    this.totalesCajaChica = '';
  }

  recargarTabla(){
    this.limpiarTabla();
    this.cargarTablaCajaChica();
  }
}
