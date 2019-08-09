import { Component, OnInit, ViewChild } from '@angular/core';
import { cajaChicaSindical } from 'src/app/modelos/cajaChicaSindical.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Definicion } from 'src/app/modelos/definicion.model';
import { CajaChicaService } from 'src/app/servicios/caja-chica.service';
import { TablaCajaChicaComponent } from './tabla-caja-chica/tabla-caja-chica.component';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { all } from 'q';
import { AniosService } from 'src/app/servicios/anios.service';
import { cajaChicaSindicalTotales } from 'src/app/modelos/cajaChicaSindicalTotales';
import { NgForm } from '@angular/forms';
import { UsuarioService } from 'src/app/servicios/usuarios.service';
import { global } from '../../servicios/global';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { element } from 'protractor';
import { DomSanitizer } from '@angular/platform-browser';

@Component({
  selector: 'app-modal-caja-chica',
  templateUrl: './modal-caja-chica.component.html',
  styleUrls: ['./modal-caja-chica.component.css']
})
export class ModalCajaChicaComponent implements OnInit {
  @ViewChild(TablaCajaChicaComponent, { static: false }) private tablaComponent: TablaCajaChicaComponent;

  //variables
  url = global.url;
  baseUrl = 'https://www.youtube.com/watch?v=3v79CLLhoyE';
  safeUrl;
  token = localStorage.getItem('token').replace(/['"]+/g, '');
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];
  idAnioActual;
  idMesActual;
  modalReference = null;
  usuario;
  rut: string = '';
  pass: string = "";
  loadingModificacion = false;
  blockCajaChica = false;

  //variables para la edicion
  idEdicion: string = '';
  campoEdicion: string = '';
  parametroEdicion: string = '';
  varType: string = 'text';
  edicionDocumento = false;
  edicionTexto = false;
  edicionArchivo;

  selectedImage: File;

  cajaChica: cajaChicaSindical[] = [];

  cajaChicaTotales: cajaChicaSindicalTotales = {
    total_ingreso: null,
    total_egreso: null,
    total: null
  }
  cajaChicaError: boolean = false;

  //iniciar loading
  loading = false;
  errorIngreso = false;
  errorIngresoFecha = false;
  ingresoStatus: string = '';

  //Valor de los select
  selectDefinicion: Definicion[] = [];

  valorAnio: Anios = {
    descripcion: '1'
  }

  valorMes: Meses = {
    descripcion: '1'
  }

  //ngModel de la caja chica

  datosCajaChica: cajaChicaSindical = {
    numero_documento: '',
    archivo_documento: null,
    fecha: '',
    descripcion: '',
    definicion: '2',
    monto_ingreso: null,
    monto_egreso: null
  }

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _cajaChicaService: CajaChicaService, private _fechasService: AniosService, private _usuariosSevice: UsuarioService, public _http: HttpClient, protected sanitizer: DomSanitizer) {
    config.backdrop = 'static';
    config.keyboard = false;
    this.safeUrl = sanitizer.bypassSecurityTrustResourceUrl(this.baseUrl);

  }

  ngOnInit() {

    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));

    //Cargar definiciones
    this.selectDefinicion = JSON.parse(localStorage.getItem('definicion'));

    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response;
        this.valorAnio.descripcion = this.idAnioActual.id;
      },
      error => {
        console.log(error);
      }
    )

    //Cargar id del Mes actual
    this._fechasService.getMesActual().subscribe(
      response => {
        this.idMesActual = response;
        this.valorMes.descripcion = this.idMesActual.id;
        this.refrescarCajaChica();
      },
      error => {
        console.log(error);
      }
    )
  }

  open(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  openCajaChica(CajaChica) {
    this.modalService.open(CajaChica, { size: 'xl' });
    //Cargar Caja chica
    this.usuarioLogeado();
  }

  changeDefinicion(evento) {
    this.datosCajaChica.definicion = evento.target.value;
  }

  changeAnio(evento) {
    this.valorAnio.descripcion = evento.target.value;
    //this.cajaChicaError = false;
    this.refrescarCajaChica();
  }

  changeMes(evento) {
    this.valorMes.descripcion = evento.target.value;
    //this.cajaChicaError = false;
    this.refrescarCajaChica();
  }

  onSelectImage(event) {
    this.datosCajaChica.archivo_documento = event.srcElement.files[0];
  }

  onSelectImageEdicion(event) {
    this.edicionArchivo = event.srcElement.files[0];
  }

  onSubmit(valid) {  //{ valid }: { valid: boolean }

    if (!valid) {
    } else {
      //llamar al modal
      this.blockCajaChica = true;
      document.getElementById("openModalButtonPass").click();
      /*  */
    }
  }

  refrescarCajaChica() {
    //Cargar Caja chica
    this.cajaChica = [];
    this._cajaChicaService.getCajaChica(this.valorAnio.descripcion, this.valorMes.descripcion).subscribe(
      response => {
        if (response.estado == "failed" || response.estado == "failed_v") {
          this.cajaChicaError = true;
          this.cajaChicaTotales.total = 0;
          this.cajaChicaTotales.total_egreso = 0;
          this.cajaChicaTotales.total_ingreso = 0;
          alert(response.mensaje);
        } else {
          this.cajaChicaError = false;
          this.cajaChica = response.caja;
          this.cajaChicaTotales = response.totales[0];
        }
        this.loading = false;
      },
      error => {
        console.log(error);
      }
    );
    this.loading = true;
  }

  //Funciones para la edicion de los campos*********************************************************

  editarParametro(id, campo, parametro){
    this.idEdicion = id;
    this.campoEdicion = campo;
    this.parametroEdicion = parametro;
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
    //aqui modificar el input del modal edicion
    document.getElementById("openModalButtonEdicion").click();
  }

  ingresarModificacionDocumento(){
    this.blockCajaChica = true;
    this.loadingModificacion = true;
    this._cajaChicaService.modificarValor(this.idEdicion, this.campoEdicion, this.edicionArchivo).subscribe(
      response => {
        if(response.estado == "failed" || response.estado == "failed_v"){
          this.blockCajaChica = false;
          this.loadingModificacion = false;
          alert(response.mensaje.input[0] + "\n " + response.mensaje.input[1]);
        }else{
          this.blockCajaChica = false;
          this.loadingModificacion = false;
          alert(response.mensaje);
          this.refrescarCajaChica();
          document.getElementById("closeModalButtonEdicion").click();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  ingresarModificacionTexto(input){
    this.blockCajaChica = true;
    this.loadingModificacion = true;
    this._cajaChicaService.modificarValor(this.idEdicion, this.campoEdicion, input).subscribe(
      response => {
        if(response.estado == "failed" || response.estado == "failed_v"){
          this.blockCajaChica = false;
          this.loadingModificacion = false;
          alert("Compruebe que la fecha nueva corresponda al mes anterior, que el numero de documento no se encuentre duplicado o no ingresar valores negativos en egreso");
          document.getElementById("closeModalButtonEdicion").click();
        }else{
          this.blockCajaChica = false;
          this.loadingModificacion = false;
          alert(response.mensaje);
          this.refrescarCajaChica();
          document.getElementById("closeModalButtonEdicion").click();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  openEdicionModal(edicion) {
    this.modalReference = this.modalService.open(edicion, { size: 'sm' });
  }


  //Funciones del modal validacion de conraseña++++++++++++++++++++++++++++++++++++++++++++++++++++++++

  openContraseniaModal(validar) {
    this.modalReference = this.modalService.open(validar, { size: 'sm' });
  }

  usuarioLogeado() {
    this._http.get(this.url + "usuario_logeado", {
      headers: new HttpHeaders(
        { 'Authorization': 'Bearer' + this.token })
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

  validarUsuario(pass){
    this._usuariosSevice.validarUsuario(this.rut, pass.value).subscribe(
      response => {
        if (response > 0){
          document.getElementById("closeModalButtonValidacion").click();
          this._cajaChicaService.ingresarValor(this.datosCajaChica).subscribe(
            response => {
              if (response.estado == 'failed_v') {
                this.blockCajaChica = false;
                alert("ERROR: Compruebe que el número de documento no se encuentre duplicado o falte un campo en el formulario");
                this.ingresoStatus = 'Se ha encontrado un error en el formulario, revisar que se encuentre todos los campos llenos y validados';
                this.errorIngreso = true;
                return false;
    
              } if (response.estado == 'failed') {
                this.blockCajaChica = false;
                alert(response.mensaje);
                this.ingresoStatus = response.mensaje;
                this.errorIngreso = true;
                return false;
              } else {
                this.blockCajaChica = false;
                alert("¡Ingreso correcto!");
                this.errorIngreso = false;
                this.ingresoStatus = '';
                this.datosCajaChica.numero_documento = '';
                this.datosCajaChica.archivo_documento = null;
                this.datosCajaChica.fecha = '';
                this.datosCajaChica.descripcion = '';
                this.datosCajaChica.monto_egreso = null;
                this.refrescarCajaChica();
              }
            },
            error => {
              console.log(<any>error);
            }
          );
        }else{
          this.blockCajaChica = false;
          alert("Acceso denegado");
              this.modalReference.close();
        }
      }
    )
  }

  test(ruta){
    console.log(this.sanitizer.bypassSecurityTrustResourceUrl(this.baseUrl + ruta));
  }

}
