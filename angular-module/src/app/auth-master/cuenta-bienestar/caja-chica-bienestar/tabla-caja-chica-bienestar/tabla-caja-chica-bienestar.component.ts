import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { BryanBienestarService } from 'src/app/servicios/bryans-bienestar.service';
import { global } from '../../../../servicios/global';
import { HttpHeaders, HttpClient } from '@angular/common/http';
import { UsuarioService } from 'src/app/servicios/usuarios.service';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-tabla-caja-chica-bienestar',
  templateUrl: './tabla-caja-chica-bienestar.component.html',
  styleUrls: ['./tabla-caja-chica-bienestar.component.css']
})
export class TablaCajaChicaBienestarComponent implements OnInit {

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
  montoInicio;

  //Variables para la modificación de documentos/////////
  /*Variables que rescatan la fila seleccionada*/
  idEdicion;
  campoEdicion;
  parametroEdicion;
  /*Variables que determinan el valor del input*/
  edicionDocumento = false;
  edicionTexto = false;
  varType;
  /*Variables que rescatan el nuevo valor a ingresar*/
  loadingModificacion = false;
  blockCajaChica = false;
  valorInput='';
  edicionArchivo;

  //Variables para el modal de validar usuario
  url = global.url;
  usuario;
  rut;
  pass;
  estado;

  //variable para asociar al modal
  modalCajaChica;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _usuariosSevice: UsuarioService, private _bienestarService: BryanBienestarService, private _fechasService: AniosService, public _http: HttpClient) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));

    this.cargarFechasActuales();

    this.usuarioLogeado();
  }

  //Abrir visor de PDF
  openPDF(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  cargarFechasActuales() {
    this.cargarDatos = 0;
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
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
    this._fechasService.getMesActual().subscribe(
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
    this._bienestarService.getCajaChicaBienestar(this.idAnioActual, this.idMesActual).subscribe(response => {
      if(response.estado == 'failed' || response.estado == 'failed_v'){
        alert(response.mensaje);
        this.cargandoTabla = false;
      }else{
        this.datosCajaChica = response;
        this.totalesCajaChica = response.total;
        this.montoInicio = response.monto_inicio;
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
    this.montoInicio = '';
  }

  recargarTabla(){
    this.limpiarTabla();
    this.cargarTablaCajaChica();
  }

  //******************CODIGO PARA LA EDICIÓN****************/


  openEdicionModal(edicion) {
    this.modalService.open(edicion, { size: 'sm' });
  }

  onSelectImageEdicion(event) {
    this.edicionArchivo = event.srcElement.files[0];
  }

  usuarioLogeado() {
    let token = localStorage.getItem('token').replace(/['"]+/g, '');
    this._http.get(this.url + "usuario_logeado", {
      headers: new HttpHeaders(
        { 'Authorization': 'Bearer' + token })
    }
    ).subscribe(
      response => {
        this.usuario = response;
        this.rut = this.usuario.rut;
      },
      error => {
        console.log(error);
      }
    )
  }

  editarParametro(id, campo, valor){
    this.idEdicion = id;
    this.campoEdicion = campo;
    this.parametroEdicion = valor;
    if(this.campoEdicion == 'fecha'){
      this.varType = 'date';
      this.edicionDocumento = false;
      this.edicionTexto = true;
    }else if (this.campoEdicion == 'archivo_documento'){
      this.edicionDocumento = true;
      this.edicionTexto = false;
    }else{
      this.varType = 'text';
      this.edicionDocumento = false;
      this.edicionTexto = true;
    }

    document.getElementById("openModalButtonEdicion").click();
  }

  ingresarModificacionTexto(input){
    this.blockCajaChica = true;
    this.loadingModificacion = true;
    this.valorInput = input;
    document.getElementById("openModalButtonPass").click();
  }

  ingresarModificacionDocumento(){
    this.blockCajaChica = true;
    this.loadingModificacion = true;
    document.getElementById("openModalButtonPass").click();
  }

  validarUsuario(pass){
    this._usuariosSevice.validarUsuario(this.rut, pass.value, this.estado).subscribe(
      response => {
        if(response > 0){
          document.getElementById("closeModalButtonValidacion").click();
          //llamar a la función para ingresar la kkck de modificación
          this.modificacionAprobada();
        }else{
          this.blockCajaChica = false;
          this.loadingModificacion = false;
          alert("Acceso denegado");
          document.getElementById("closeModalButtonEdicion").click();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  modificacionAprobada(){
    this._bienestarService.updateCampoCajaChica(this.idEdicion, this.campoEdicion, this.valorInput).subscribe(
      response => {
        if(response.estado == "failed" || response.estado == "failed_v"){
          this.blockCajaChica = false;
          this.loadingModificacion = false;
          alert(response.mensaje.input[0] + "\n " + response.mensaje.input[1]);
        }else{
          this.blockCajaChica = false;
          this.loadingModificacion = false;
          alert(response.mensaje);
          this.cargarTablaCajaChica();
          document.getElementById("closeModalButtonEdicion").click();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

}
