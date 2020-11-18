import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AniosService } from 'src/app/servicios/anios.service';
import { ArchivadorBienestarService } from '../../../servicios/archivador-bienestar.service';


@Component({
  selector: 'app-archivador-bienestar',
  templateUrl: './archivador-bienestar.component.html',
  styleUrls: ['./archivador-bienestar.component.css']
})
export class ArchivadorBienestarComponent implements OnInit {

  url
  botonIngresar = false;

  remitente = ''
  selectAnio
  selectMes
  idAnioActualN
  idMesActualN
  idAnioActualA
  idMesActualA
  cargarDatos = 0

  selectTipo = [
    {
      id: 1,
      tipo: "Reporte"
    },
    {
      id: 2,
      tipo: "Descuento"
    },
    {
      id: 4,
      tipo: "Otros"
    }
  ];

  ingresandoNota = false;
  ingresandoArchivo = false;

  notas = []
  archivos = []

  datosNotas = {
    fecha: '',
    nota: ''
  }

  datosArchivos = {
    fecha: '',
    tipo: 0,
    titulo: '',
    archivo: ''
  }

  edicionDocumento = false;
  edicionSelect = false;
  edicionTexto = false;
  nuevoIngreso = false;
  varType;

  datosEdicion = {
    id: '',
    campo: '',
    input: ''
  }

  valorInput = '';
  edicionArchivo;
  selectEdicionTipo = 0;

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _archivadorService: ArchivadorBienestarService, private _fechasService: AniosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  open(content) {
    this.modalService.open(content, { size: 'xl' });
    this.cargarFecha();
    this.cargarFechasActuales();
    /* this.traerTipos(); */
  }

  openExcel(content) {
    this.modalService.open(content, { size: 'lg' });
  }

  openEdicionModal(edicion) {
    this.modalService.open(edicion, { size: 'sm' });
  }


  ngOnInit() {
    this.url = document.location.origin + '/';
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
  }

  archivo(event) {
    this.datosArchivos.archivo = event.srcElement.files[0];
  }

  archivoEdicion(event) {
    this.edicionArchivo = event.srcElement.files[0];
  }

  changeAnio(valorSelect, tipo) {
    if (tipo == 1) {
      this.idAnioActualN = valorSelect.target.value;
      this.notas = [];
      this.traerNotas();
    } else {
      this.idAnioActualA = valorSelect.target.value;
      this.archivos = [];
      this.traerArchivos();
    }
  }

  changeMes(valorSelect, tipo) {
    if (tipo == 1) {
      this.idMesActualN = valorSelect.target.value;
      this.notas = [];
      this.traerNotas();
    } else {
      this.idMesActualA = valorSelect.target.value;
      this.archivos = [];
      this.traerArchivos();
    }
  }

  cargarFecha() {
    this._archivadorService.traerDatos().subscribe(
      res => {
        if (res.estado == 'success') {
          this.remitente = res.datos.nombre;
          this.datosNotas.fecha = res.datos.fecha;
          this.datosArchivos.fecha = res.datos.fecha;
        } else {
          alert(res.mensaje);
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  cargarFechasActuales() {
    this.cargarDatos = 0;
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActualN = response.id;
        this.idAnioActualA = response.id;
        this.cargarDatos++;
        console.log(this.cargarDatos);
        if (this.cargarDatos == 2) {
          this.traerNotas();
          this.traerArchivos();
        }
      },
      error => {
        console.log(error);
      }
    )
    //Cargar id del Mes actual
    this._fechasService.getMesActual().subscribe(
      response => {
        this.idMesActualN = response.id;
        this.idMesActualA = response.id;
        this.cargarDatos++;
        console.log(this.cargarDatos);
        if (this.cargarDatos == 2) {
          this.traerNotas();
          this.traerArchivos();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  ingresarNota() {
    this.ingresandoNota = true;
    this._archivadorService.ingresarNota(this.datosNotas).subscribe(
      res => {
        if (res.estado = 'success') {
          this.ingresandoNota = false;
          this.limpiarNotas();
          this.traerNotas();
          alert(res.mensaje);
        } else {
          this.ingresandoNota = false;
          alert(res.mensaje);
        }
      }, error => {
        this.ingresandoNota = false;
        console.log(error);
      }
    )
  }

  actualizarNota() {
    this._archivadorService.actualizarNota(this.datosEdicion, this.valorInput).subscribe(
      res => {
        if (res.estado == 'success') {
          this.traerNotas();
          document.getElementById("closeModalButtonEdicion").click();
          alert(res.mensaje);
        } else {
          alert(res.mensaje);
        }
      }, error => {
        console.log(error);
      }
    )
  }

  limpiarNotas() {
    this.datosNotas.nota = '';
  }

  traerNotas() {
    this.notas = [];
    this._archivadorService.traerNotas(this.idAnioActualN, this.idMesActualN).subscribe(
      res => {
        if (res.estado == 'success') {
          this.notas = res.notas;
        } else {
          alert(res.mensaje);
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  traerTipos() {
    this._archivadorService.traerTipos().subscribe(
      res => {
        if (res.estado == 'success') {
          this.selectTipo = res.tipos;
        } else {
          alert(res.mensaje);
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  limpiarArchivos() {
    this.datosArchivos.tipo = 0;
    this.datosArchivos.titulo = '';
    this.datosArchivos.archivo = null;
  }

  ingresarArchivo() {
    this.ingresandoArchivo = true;
    if (this.datosArchivos.tipo == 0) {
      this.ingresandoArchivo = false;
      alert("Debe seleccionar un tipo.");
    } else {
      this._archivadorService.ingresarArchivo(this.datosArchivos).subscribe(
        res => {
          if (res.estado == 'success') {
            this.ingresandoArchivo = false;
            this.limpiarArchivos();
            this.traerArchivos();
            alert(res.mensaje);
          } else {
            this.ingresandoArchivo = false;
            alert(res.mensaje);
          }
        }, error => {
          this.ingresandoArchivo = false;
          console.log(error);
        }
      )
    }
  }

  actualizarArchivo() {
    let valor;
    switch (this.datosEdicion.campo) {
      case 'fecha':
        valor = this.valorInput;
        break;
      case 'titulo':
        valor = this.valorInput;
        break;
      case 'tipo':
        if (valor == 0) {
          alert("Debe seleccionar un tipo.");
        } else {
          valor = this.selectEdicionTipo;
        }
        break;
      case 'archivo':
        valor = this.edicionArchivo;
        break;

      default:
        break;
    }

    this._archivadorService.actualizarArchivo(this.datosEdicion, valor).subscribe(
      res => {
        if (res.estado == 'success') {
          this.traerArchivos();
          document.getElementById("closeModalButtonEdicion").click();
          alert(res.mensaje);
        } else {
          alert(res.mensaje);
        }
      }, error => {
        console.log(error);
      }
    )
  }

  traerArchivos() {
    this.archivos = [];
    this._archivadorService.traerArchivos(this.idAnioActualA, this.idMesActualA).subscribe(
      res => {
        if (res.estado == 'success') {
          this.archivos = res.archivos;
        } else {
          alert(res.mensaje);
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  editarParametro(id, campo, valor, estado) {

    if (estado == true) {
      this.botonIngresar = true;
    } else {
      this.botonIngresar = false;
    }
    this.datosEdicion.id = id;
    this.datosEdicion.campo = campo;
    this.datosEdicion.input = valor;

    switch (campo) {
      case 'fecha':
        this.varType = 'date';
        this.edicionDocumento = false;
        this.edicionTexto = true;
        this.edicionSelect = false;
        this.nuevoIngreso = false;
        break;

      case 'titulo':
        this.varType = 'text';
        this.edicionDocumento = false;
        this.edicionTexto = true;
        this.nuevoIngreso = false;
        this.edicionSelect = false;
        break;

      case 'descripcion':
        this.varType = 'text';
        this.edicionDocumento = false;
        this.edicionTexto = true;
        this.nuevoIngreso = false;
        this.edicionSelect = false;
        break;

      case 'tipo':
        this.edicionSelect = true;
        this.edicionDocumento = false;
        this.edicionTexto = false;
        this.nuevoIngreso = false;
        break;

      case 'archivo':
        this.edicionDocumento = true;
        this.edicionSelect = false;
        this.edicionTexto = false;
        this.nuevoIngreso = false;
        break;

      default:
        break;
    }
    document.getElementById("openEdicionArchivo").click();
  }


}
