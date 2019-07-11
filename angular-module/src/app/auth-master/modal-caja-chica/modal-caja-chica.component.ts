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

@Component({
  selector: 'app-modal-caja-chica',
  templateUrl: './modal-caja-chica.component.html',
  styleUrls: ['./modal-caja-chica.component.css']
})
export class ModalCajaChicaComponent implements OnInit {
  @ViewChild(TablaCajaChicaComponent, { static: false }) private tablaComponent: TablaCajaChicaComponent;

  //variables
  url = global.url;
  token = localStorage.getItem('token').replace(/['"]+/g, '');
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];
  idAnioActual;
  idMesActual;
  modalReference = null;
  usuario;
  rut: string = '';
  pass: string = "";

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

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _cajaChicaService: CajaChicaService, private _fechasService: AniosService, private _usuariosSevice: UsuarioService, public _http: HttpClient) {
    config.backdrop = 'static';
    config.keyboard = false;

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
        console.log(this.idMesActual);
        this.valorMes.descripcion = this.idMesActual.id;
      },
      error => {
        console.log(error);
      }
    )
  }

  openCajaChica(CajaChica) {
    this.modalService.open(CajaChica, { size: 'lg' });
    //Cargar Caja chica
    this.refrescarCajaChica();
    this.usuarioLogeado();
  }

  changeDefinicion(evento) {
    this.datosCajaChica.definicion = evento.target.value;
  }

  changeAnio(evento) {
    this.valorAnio.descripcion = evento.target.value;
    this.cajaChicaError = false;
    this.refrescarCajaChica();
  }

  changeMes(evento) {
    this.valorMes.descripcion = evento.target.value;
    this.cajaChicaError = false;
    this.refrescarCajaChica();
  }

  onSelectImage(event) {
    this.datosCajaChica.archivo_documento = event.srcElement.files[0];
  }

  onSubmit(valid) {  //{ valid }: { valid: boolean }

    if (!valid) {
      console.log("Ingreso no valido revisar campos");
    } else {
      //llamar al modal
      console.log(this.datosCajaChica);
      document.getElementById("openModalButtonPass").click();
      /*  */
    }
  }

  refrescarCajaChica() {
    //Cargar Caja chica
    this.cajaChica = [];
    this._cajaChicaService.getCajaChica(this.valorAnio.descripcion, this.valorMes.descripcion).subscribe(
      response => {
        if (response.estado == "failed" || response.estado == false || response.estado == "failed_v") {
          this.cajaChicaError = true;
        } else {
          this.cajaChica = response;
        }
        this.loading = false;
      },
      error => {
        console.log(error);
      }
    );
    this._cajaChicaService.getTotalesCajaChica(this.valorAnio.descripcion, this.valorMes.descripcion).subscribe(
      response => {
        if (response.estado == "failed" || response.estado == false || response.estado == "failed_v") {
          this.cajaChicaError = true;
        } else {
          this.cajaChicaTotales = response;
          console.log(this.cajaChicaTotales);
        }
        this.loading = false;
      },
      error => {
        console.log(error);
      }
    )
    this.loading = true;
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
        console.log(this.usuario);
      },
      error => {
        console.log(error);
      }
    )
  }

  validarUsuario(pass){
    console.log(this.rut + "+" + pass.value);
    this._usuariosSevice.validarUsuario(this.rut, pass.value).subscribe(
      response => {
        if (response > 0){
          console.log("Aquí va el ingreso")
          console.log(this.rut + "+" + pass.value);
          this._cajaChicaService.ingresarValor(this.datosCajaChica).subscribe(
            response => {
              if (response.estado == 'failed_v') {
                this.ingresoStatus = 'Error, Compruebe que los datos ingresados sean correctos y no duplicados.';
                this.errorIngreso = true;
                return false;
    
              } if (response.estado == 'failed') {
                this.ingresoStatus = response.mensaje;
                this.errorIngreso = true;
                return false;
              } else {
                console.log("Ingreso correcto");
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
          console.log("datos incorrectos");
          console.log(this.rut + "+" + pass.value);
          alert("Acceso denegado");
              this.modalReference.close();
        }
      }
    )
  }

}
