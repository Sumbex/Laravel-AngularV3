import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { VotacionesBryanmService } from 'src/app/votaciones-bryanm.service';
import { AniosService } from 'src/app/servicios/anios.service';
import { Chart } from 'chart.js';

@Component({
  selector: 'app-temas',
  templateUrl: './temas.component.html',
  styleUrls: ['./temas.component.css']
})
export class TemasComponent implements OnInit {

  chart = [];
  totalVotos = [];
  cantVotos;

  remitente;
  ingresandoTema = false;
  actualizandoTema = false;
  terminarTema = false;
  cancelarTema = false;
  mostrarVotos = false;
  cerrada = false;

  mostrarMotivo = false;
  motivo;

  idAnioActual;
  idMesActual;
  idTipoActual = 0;

  selectAnio;
  selectMes;
  selectTipo;

  cargarSelect = 0;

  datosTema = {
    fecha: '',
    titulo: '',
    descripcion: '',
    tipo_votacion: '3',
  };

  datosActTemas = {
    id: '',
    fecha: '',
    titulo: '',
    descripcion: ''
  };

  datosDetTemas = {
    id: '',
    inicio: '',
    termino: '',
    titulo: '',
    descripcion: '',
    estado: '',
    aprobado: '',
    nombre: '',
    motivo: ''
  };

  temasActivos = [];
  temasHistorial = [];

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _votaciones: VotacionesBryanmService, private _fechasService: AniosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
  }

  abrirModalIngreso(modal) {
    this.modalService.open(modal, { size: 'xl' });
    this.cargarDatos();
  }

  abrirModalActivos(modal) {
    this.modalService.open(modal, { size: 'xl' });
    this.traerTemasActivos();
  }

  abrirModalHistorial(modal) {
    this.modalService.open(modal, { size: 'xl' });
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
    this.cargarSelectHistorial();
  }

  abrirModalActualizar(modal, tema) {
    this.cargarDatosActualizar(tema);
    this.modalService.open(modal, { size: 'xl' });
  }

  abrirModalVotos(modal) {
    this.modalService.open(modal, { size: 'lg' });
    this.votos();
  }

  abrirModalDetalle(modal, tema) {
    this.cargarDatosDetalle(tema);
    this.modalService.open(modal, { size: 'xl' });
  }

  cargarDatosDetalle(tema) {
    this.datosDetTemas.id = tema.id;
    this.datosDetTemas.inicio = tema.fecha_inicio;
    this.datosDetTemas.termino = tema.fecha_termino;
    this.datosDetTemas.titulo = tema.titulo;
    this.datosDetTemas.descripcion = tema.descripcion;
    this.datosDetTemas.estado = tema.estado;
    this.datosDetTemas.aprobado = tema.aprobado;
    this.datosDetTemas.nombre = tema.nombre;
    this.datosDetTemas.motivo = tema.motivo;
    this.motivo = tema.motivo;
  }

  cargarDatosActualizar(tema) {
    this.datosActTemas.id = tema.id;
    this.remitente = tema.nombre;
    this.datosActTemas.fecha = tema.fecha;
    this.datosActTemas.titulo = tema.titulo;
    this.datosActTemas.descripcion = tema.descripcion;
  }

  changeAnio(valorSelect) {
    this.idAnioActual = valorSelect.target.value;
    this.traerHistorial();
  }

  changeMes(valorSelect) {
    this.idMesActual = valorSelect.target.value;
    this.traerHistorial();
  }

  changeTipo(valorSelect) {
    this.idTipoActual = valorSelect.target.value;
    this.traerHistorial();
  }

  cargarSelectHistorial() {
    this.cargarSelect = 0;
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response.id;
        this.cargarSelect++;
        if (this.cargarSelect == 3) {
          this.traerHistorial();
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
        this.cargarSelect++;
        if (this.cargarSelect == 3) {
          this.traerHistorial();
        }
      },
      error => {
        console.log(error);
      }
    )
    //Cargar Tipos
    this._votaciones.traerTipos().subscribe(
      res => {
        this.selectTipo = res.tipos;
        this.cargarSelect++;
        if (this.cargarSelect == 3) {
          this.idTipoActual = 0;
          this.traerHistorial();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  cargarDatos() {
    this._votaciones.traerDatos().subscribe(
      res => {
        if (res.estado == 'success') {
          this.remitente = res.datos.nombre;
          this.datosTema.fecha = res.datos.fecha;
        } else {
          alert(res.mensaje);
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  limpiarTema() {
    this.datosTema.titulo = '';
    this.datosTema.descripcion = '';
    this.datosTema.tipo_votacion = '3';
  }

  ingresarTema() {
    this.ingresandoTema = true;
    console.log(this.datosTema)
    //return false;
    this._votaciones.ingresarTema(this.datosTema).subscribe(
      res => {
        if (res.estado == 'success') {
          this.limpiarTema();
          this.ingresandoTema = false;
          alert(res.mensaje);
        } else {
          this.ingresandoTema = false;
          alert(res.mensaje);
        }
      }, error => {
        this.ingresandoTema = false;
        console.log(error);
      }
    )
  }

  actualizarTema() {
    this.actualizandoTema = true;
    this._votaciones.actualizarTema(this.datosActTemas).subscribe(
      res => {
        if (res.estado = 'success') {
          this.traerTemasActivos();
          this.actualizandoTema = false;
          alert(res.mensaje);
        } else {
          this.actualizandoTema = false;
          alert(res.mensaje);
        }
      }, error => {
        this.actualizandoTema = false;
        console.log(error);
      }
    )
  }

  cancelarTemas() {
    if (confirm('¿Estas seguro de querer cancelar el tema?')) {
      this.cancelarTema = true;
      this._votaciones.cancelarTema(this.datosActTemas.id).subscribe(
        res => {
          if (res.estado = 'success') {
            this.traerTemasActivos();
            this.cancelarTema = false;
            alert(res.mensaje);
            document.getElementById("cerrarModalEditar").click();
          } else {
            this.cancelarTema = false;
            alert(res.mensaje);
          }
        }, error => {
          this.cancelarTema = false;
          console.log(error);
        }
      )
    } else {
      this.cancelarTema = false;
    }
  }

  cerrarTema(grafico) {
    if (confirm('¿Estas seguro de querer terminar la votacion?')) {
      this.terminarTema = true;
      this._votaciones.cerrarTema(this.datosActTemas.id).subscribe(
        res => {
          if (res.estado = 'success') {
            this.terminarTema = false;
            this.cerrada = true;
            this.motivo = res.motivo;
            this.traerVotosTema(this.datosActTemas.id, grafico, true);
            alert(res.mensaje);
          } else {
            this.terminarTema = false;
            alert(res.mensaje);
          }
        }, error => {
          this.terminarTema = false;
          console.log(error);
        }
      )
    } else {
      this.terminarTema = false;
    }
  }

  traerTemasActivos() {
    this.temasActivos = [];
    this.cerrada = false;
    this._votaciones.traerTemasActivos().subscribe(
      res => {
        if (res.estado == 'success') {
          this.temasActivos = res.temas;
        } else {
          alert(res.mensaje);
          document.getElementById("cerrarActivos").click();
        }
      }, error => {
        console.log(error);
      }
    )
  }

  traerHistorial() {
    this.temasHistorial = [];
    this._votaciones.traerHistorialTemas(this.idAnioActual, this.idMesActual, this.idTipoActual).subscribe(
      res => {
        if (res.estado == 'success') {
          this.temasHistorial = res.temas;
        } else {
          alert(res.mensaje);
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  traerVotosTema(tema, grafico, estado) {
    this.mostrarVotos = true;
    this.mostrarMotivo = false;
    if (estado == true) {
      this.mostrarMotivo = true;
    } else {
      this.motivo = '';
      this.mostrarMotivo = false;
    }
    this.totalVotos = [];
    this.cantVotos = '';
    this._votaciones.traerVotosTema(tema).subscribe(
      res => {
        if (res.estado == 'success') {
          this.mostrarVotos = false;
          this.totalVotos = res.grafico;
          this.cantVotos = res.total;
          this.abrirModalVotos(grafico);
        } else {
          this.mostrarVotos = false;
        }
      }, error => {
        this.mostrarVotos = false;
        console.log(error);
      }
    )
  }

  votos() {
    this.chart = new Chart("votosTema", {
      type: 'pie',
      data: {
        labels: ['Apruebo', 'Rechazo', 'Me Abstengo', 'Socios sin Votar'],
        datasets: [{
          label: '# of Votes',
          data: this.totalVotos,
          backgroundColor: [
            'rgb(92,184,92)',
            'rgb(217,83,79)',
            'rgb(240,173,78)',
            'rgb(91,192,222)'
          ],
          borderColor: [
            'rgb(92,184,92)',
            'rgb(217,83,79)',
            'rgb(240,173,78)',
            'rgb(91,192,222)'
          ],
          borderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
          dispaly: true
        },
      }
    })
  }


}
