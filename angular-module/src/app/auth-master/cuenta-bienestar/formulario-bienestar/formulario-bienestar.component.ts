import { Component, OnInit } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
import { BienestarService } from 'src/app/servicios/bienestar.service';

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

  //variables cuenta gas
  InsertarCuentaBienestar = {
    fecha:'',
    tipo_cuenta_bienestar_id: '',
    numero_documento_1: '',
    archivo_documento_1: null,
    monto: '',
    definicion: '',
    descripcion: '',
  }
  blockIngreso: boolean = false;

  constructor(
    private _SociosService: SociosService,
    private _validarusuario: ValidarUsuarioService,
    private _bienestarService: BienestarService) {
  }
  ngOnInit() {
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
      if (response.estado == 'success') {
        this.InsertarCuentaBienestar.fecha = '';
        this.InsertarCuentaBienestar.tipo_cuenta_bienestar_id = '';
        this.InsertarCuentaBienestar.numero_documento_1 = '';
        this.InsertarCuentaBienestar.archivo_documento_1 = '';
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
      }
    );

  }
}

