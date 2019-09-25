import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { BryanBienestarService } from 'src/app/servicios/bryans-bienestar.service';

@Component({
  selector: 'app-cuenta-bienestar-socios',
  templateUrl: './cuenta-bienestar-socios.component.html',
  styleUrls: ['./cuenta-bienestar-socios.component.css']
})
export class CuentaBienestarSociosComponent implements OnInit {

  //Variables para los select de año y mes
  selectAnio;
  selectMes;

  idAnioActual;
  idMesActual;

  //Variable para las cargas
  cargandoTabla = false;
  cargarDatos = 0;

  //Variable que almacena los valores obtenidos para la Cuenta Bienestar
  montoInicio;
  datosGas;
  datosReunion;
  datosVotacion;
  datosCajaChica;
  datosNacimiento;
  datosFallecimientos;
  datosMedico;
  datosCuotaSocio;
  datosElecciones;
  datosConsorcio;
  datosExtraordinaria;
  datosSindicalizados;
  montosTotales;

  //variable para asociar al modal
  modalCajaChica;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _portalSociosService: PortalSociosService, private _bienestarService: BryanBienestarService) {
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
    this.modalService.open(content, { size: 'lg' });
  }

  cargarFechasActuales() {
    this.cargarDatos = 0;
    //Cargar id del Año actual
    this._portalSociosService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
        this.cargarDatos++;
        console.log(this.cargarDatos);
        if (this.cargarDatos == 2) {
          this.cargarCuentaBienestar();
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
        if (this.cargarDatos == 2) {
          this.cargarCuentaBienestar();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  changeAnio(valorSelect) {
    this.limpiarTabla();
    this.idAnioActual = valorSelect.target.value;
    this.recargarTabla();
  }

  changeMes(valorSelect) {
    this.limpiarTabla();
    this.idMesActual = valorSelect.target.value;
    this.recargarTabla();
  }

  cargarCuentaBienestar() {
    this.cargandoTabla = true;
    this._bienestarService.getCuentaBienestar(this.idAnioActual, this.idMesActual).subscribe(response => {
      if (response.estado == 'failed' || response.estado == 'failed_v') {
        alert(response.mensaje);
        this.cargandoTabla = false;
      } else {
        this.montoInicio = response.monto_inicio;
        this.datosGas = response.CB.gas;
        this.datosReunion = response.CB.reunion;
        this.datosVotacion = response.CB.votacion;
        this.datosCajaChica = response.CB.caja_chica;
        this.datosNacimiento = response.CB.nacimientos;
        this.datosFallecimientos = response.CB.fallecimientos;
        console.log(this.datosFallecimientos);
        this.datosMedico = response.CB.gastos_medicos;
        this.datosCuotaSocio = response.CB.cuota_sindical;
        this.datosElecciones = response.CB.elecciones;
        this.datosConsorcio = response.CB.consorcio;
        this.datosExtraordinaria = response.CB.cuota_extra;
        this.datosSindicalizados = response.CB.no_sindicalizados;
        this.montosTotales = response.totales;
        this.cargandoTabla = false;
      }
    },
      error => {
        console.log(error);
        this.cargandoTabla = false;
      });
  }

  limpiarTabla() {
    this.montoInicio = '';
    this.datosGas = null;
    this.datosReunion = null;
    this.datosVotacion = null;
    this.datosCajaChica = null;
    this.datosNacimiento = null;
    this.datosFallecimientos = null;
    this.datosMedico = null;
    this.datosCuotaSocio = null;
    this.datosElecciones = null;
    this.datosConsorcio = null;
    this.datosExtraordinaria = null;
    this.datosSindicalizados = null;
    this.montosTotales = '';
  }

  recargarTabla() {
    this.limpiarTabla();
    this.cargarCuentaBienestar();
  }

}
