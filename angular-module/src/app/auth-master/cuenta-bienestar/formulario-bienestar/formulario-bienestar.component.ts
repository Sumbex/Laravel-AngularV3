import { Component, OnInit } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
import { BienestarService } from 'src/app/servicios/bienestar.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-formulario-bienestar',
  templateUrl: './formulario-bienestar.component.html',
  styleUrls: ['./formulario-bienestar.component.css']
})
export class FormularioBienestarComponent implements OnInit {
  //Variables buscador socio
  blockSocio: boolean = false;
  rut: '';
  fechaSocioTest: '';
  rutSocioTest: '';
  idSocio: '';
  nombreSocioTest = '';
  nombreUpperSocio = '';
  ocultarBuscador = true;
  ocultarNac = true;
  ocultarFalle = true;
  ocultarMed = true;

  //variables cuenta Bienestar
  blockIngreso: boolean = false;
  InsertarCuentaBienestar = {
    socioId: '0',
    fecha: '',
    tipo_cuenta_bienestar_id: '',
    numero_documento_1: '',
    archivo_documento_1: null,
    monto: '',
    definicion: '',
    descripcion: '',
  }
  //varibles modal permisos usuario
  user: object = [];
  load: boolean = false;
  validarFormBienestar = null;

  constructor(
    private _SociosService: SociosService,
    private _validarusuario: ValidarUsuarioService,
    private _bienestarService: BienestarService,
    config: NgbModalConfig,
    private modalService: NgbModal) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    this.usuario_logeado();
  }

  buscarSocio() {
    this.blockSocio = true;
    this._SociosService.traerDatosSocio(this.rut).subscribe((response) => {
      console.log(response);
      if (response.estado == "failed") {
        alert('Error, El rut ingresado no existe en nuestra base de datos, pruebe digitando otro rut.');
        this.rut = '';
        this.fechaSocioTest = '';
        this.rutSocioTest = '';
        this.nombreSocioTest = '';
        this.idSocio = '';
        this.blockSocio = false;
        return false;
      } else {
        this.rut = '';
        this.fechaSocioTest = response.fecha_nacimiento;
        this.rutSocioTest = response.rut;
        this.nombreSocioTest = response.nombres + ' ' + response.a_paterno + ' ' + response.a_materno;
        this.nombreUpperSocio = this.nombreSocioTest.toUpperCase();
        this.idSocio = response.id;
        this.blockSocio = false;
      }
    },
      error => {
        console.log(error);
        this.blockSocio = false;
      }
    );

  }

  ocultarBuscadorSocio(valor) {
    if (valor == true) {
      this.ocultarBuscador = true;
      this.ocultarNac = true;
      this.ocultarFalle = true;
      this.ocultarMed = true;
    } else {
      this.ocultarBuscador = false;
      this.ocultarNac = false;
      this.ocultarFalle = false;
      this.ocultarMed = false;
    }
  }

  onSelectPDF(event) {
    this.InsertarCuentaBienestar.archivo_documento_1 = event.srcElement.files[0];
  }

  guardarCuentaBienestar() {
    if (this.InsertarCuentaBienestar == null) {
      alert('ingrese los datos obligatorios (*)');
      return false;
    }
    this.blockIngreso = true;
    const data = new FormData();
    data.append('socio_id', this.InsertarCuentaBienestar.socioId);
    data.append('fecha', this.InsertarCuentaBienestar.fecha);
    data.append('tipo_cuenta_bienestar_id', this.InsertarCuentaBienestar.tipo_cuenta_bienestar_id);
    data.append('numero_documento_1', this.InsertarCuentaBienestar.numero_documento_1);
    data.append('archivo_documento_1', this.InsertarCuentaBienestar.archivo_documento_1);
    data.append('monto', this.InsertarCuentaBienestar.monto);
    data.append('definicion', this.InsertarCuentaBienestar.definicion);
    data.append('descripcion', this.InsertarCuentaBienestar.descripcion);

    this._bienestarService.insertar_CuentaBienestar(data).subscribe((response) => {
      if (response.estado == 'failed') {
        alert(response.mensaje);
        this.blockIngreso = false;
        return false;
      }
      if (response.estado == 'failed_v') {
        alert(response.mensaje);
        this.blockIngreso = false;
        return false;
      }
      if (response.estado == 'success') {
        this.InsertarCuentaBienestar.socioId = '0';
        this.InsertarCuentaBienestar.fecha = '';
        this.InsertarCuentaBienestar.tipo_cuenta_bienestar_id = '';
        this.InsertarCuentaBienestar.numero_documento_1 = '';
        this.InsertarCuentaBienestar.archivo_documento_1 = null;
        this.InsertarCuentaBienestar.monto = '';
        this.InsertarCuentaBienestar.definicion = '';
        this.InsertarCuentaBienestar.descripcion = '';
        alert(response.mensaje);
        this.blockIngreso = false;
        return false;
      }
    },
      error => {
        console.log(error);
        this.blockIngreso = false;
        return false;
      }
    );

  }

  usuario_logeado() {

    this._validarusuario.usuario_logeado().subscribe((val: object) => {

      this.user = val;

    }, response => { console.log("POST call in error", response); }, () => {
      console.log("The POST success.");
    });
  }

  btn_validar_usuario($rut, $password, validar) {//btn que esta en el modal de validacion de usuario
    this.load = true;
    const formData = new FormData();
    formData.append('rut', $rut.value);
    formData.append('password', $password.value);
    formData.append('estado', 'ingresar_cb');

    this._validarusuario.validar_usuario(formData).subscribe((val) => {

      if (val > 0) {//si tiene acceso;

        this.load = false;
        this.guardarCuentaBienestar();
        this.validarFormBienestar.close();
      } else {
        alert("Acceso denegado");
        this.load = false;
        this.validarFormBienestar.close();
      }

    }, response => { console.log("POST call in error", response); }, () => {
      console.log("The POST success.");
    });
  }



  validar_usuario(modalUsuario) {
    this.validarFormBienestar = this.modalService.open(modalUsuario, { size: 'sm' });

  }
}

