import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SindicalService } from '../servicios/sindical.service';
import { AniosService } from '../servicios/anios.service';
import { UsuarioService } from '../servicios/usuarios.service';

@Component({
  selector: 'app-modal-gastos-operacionales',
  templateUrl: './modal-gastos-operacionales.component.html',
  styleUrls: ['./modal-gastos-operacionales.component.css']
})
export class ModalGastosOperacionalesComponent implements OnInit {

  //Variables para los select de año y mes
  selectAnio;
  selectMes;

  idAnioActual;
  idMesActual;

  //Variable para las cargas
  cargandoTabla = false;
  cargarDatos = 0;

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
  valorInput = '';
  edicionArchivo;

  //Datos del Formulario
  datosFormulario = {
    numero_documento: '',
    archivo_documento: null,
    fecha: '',
    descripcion: '',
    definicion: '2',
    monto: null
  }

  //Rut del usuario
  rutUsuario;

  //Variables para el modal de validar usuario
  usuario;
  rut;
  pass;
  estado;

  //variabes para determinar si se guarda archivo o texto 
  edText;
  edFile;

  //Datos para la tabla
  datosGastosOperacionales;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _usuariosSevice: UsuarioService, private _service: SindicalService, private _fechasService: AniosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));

    //Obtener Rut
    this.rutUsuario = JSON.parse(localStorage.getItem('usuario'));
    console.log(this.rutUsuario);
  }

  //Abrir visor de PDF
  openPDF(content) {
    this.modalService.open(content, { size: 'lg' });
  }

  //Abrir confirmacion contraseña
  openEdicionModal(edicion) {
    this.modalService.open(edicion, { size: 'sm' });
  }

  cargarFechasActuales() {
    this.cargarDatos = 0;
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
        this.cargarDatos++;
        console.log(this.cargarDatos);
        if (this.cargarDatos == 2) {
          this.recargarTabla();
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
        if (this.cargarDatos == 2) {
          this.recargarTabla();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  openModal(CajaChica) {
    this.modalService.open(CajaChica, { size: 'xl' });
    this.cargarFechasActuales();
  }

  onSelectImage(event) {
    this.datosFormulario.archivo_documento = event.srcElement.files[0];
  }

  onSelectImageEdicion(event) {
    this.edicionArchivo = event.srcElement.files[0];
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

  limpiarTabla() {
    this.datosGastosOperacionales = '';
  }

  recargarTabla() {
    this.cargarTablaCajaChica();
    this.limpiarTabla();
  }

  cargarTablaCajaChica() {
    this.cargandoTabla = true;
    this._service.getGastosOperacionales(this.idAnioActual, this.idMesActual).subscribe(response => {
      if (response.estado == 'failed' || response.estado == 'failed_v') {
        alert(response.mensaje);
        this.cargandoTabla = false;
      } else {
        this.datosGastosOperacionales = response;
        this.cargandoTabla = false;
      }
    },
      error => {
        console.log(error);
        this.cargandoTabla = false;
      });
  }

  ingresarValor() {
    console.log(this.datosFormulario);
    this._service.setGastoSindical(this.datosFormulario).subscribe(response => {
      if (response.estado == 'failed' || response.estado == 'failed_v') {
        alert(JSON.stringify(response.mensaje));
      } else {
        alert(response.mensaje);
        this.recargarTabla();
      }
    }, error => {
      console.log(error);
    });
  }

  //almacenar datos al querer editar un valor
  editarParametro(id, campo, valor) {
    this.idEdicion = id;
    this.campoEdicion = campo;
    this.parametroEdicion = valor;
    if (this.campoEdicion == 'fecha') {
      console.log('Estoy pasando por fecha');
      this.varType = 'date';
      this.edicionDocumento = false;
      this.edicionTexto = true;
    } else if (this.campoEdicion == 'archivo_documento') {
      console.log('Estoy pasando por archivo');
      this.edicionDocumento = true;
      this.edicionTexto = false;
    } else {
      console.log('Estoy pasando por text');
      this.varType = 'text';
      this.edicionDocumento = false;
      this.edicionTexto = true;
    }

    document.getElementById("openModalButtonEdicionOperacional").click();
  }

  ingresarModificacionTexto(input) {
    this.edText = true;
    this.edFile = false;
    this.blockCajaChica = true;
    this.loadingModificacion = true;
    this.valorInput = input;
    document.getElementById("openModalButtonPassOperacionales").click();
  }

  ingresarModificacionDocumento() {
    this.edText = false;
    this.edFile = true;
    this.blockCajaChica = true;
    this.loadingModificacion = true;
    document.getElementById("openModalButtonPassOperacionales").click();
  }

  validarUsuario(pass) {
    /* this._usuariosSevice.validarUsuario(this.rut, pass.value, this.estado).subscribe(
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
    ) */
    if (this.edicionTexto) {
      this.modificacionAprobada();
    } else if (this.edicionArchivo) {
      this.modificacionAprobadaArchivo();
    }
  }

  modificacionAprobada() {
    this._service.updateCampoOperacional(this.idEdicion, this.campoEdicion, this.valorInput).subscribe(
      response => {
        if (response.estado == "failed" || response.estado == "failed_v") {
          this.blockCajaChica = false;
          this.loadingModificacion = false;
          alert(JSON.stringify(response.mensaje));
        } else {
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

  modificacionAprobadaArchivo() {
    this._service.updateCampoOperacional(this.idEdicion, this.campoEdicion, this.edicionArchivo).subscribe(
      response => {
        if (response.estado == "failed" || response.estado == "failed_v") {
          this.blockCajaChica = false;
          this.loadingModificacion = false;
          alert(JSON.stringify(response.mensaje));
        } else {
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
