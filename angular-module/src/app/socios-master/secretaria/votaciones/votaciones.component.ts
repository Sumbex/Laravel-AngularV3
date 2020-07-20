import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { VotacionesBryanmService } from 'src/app/votaciones-bryanm.service';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';
import { Chart } from 'chart.js';

@Component({
  selector: 'app-votaciones',
  templateUrl: './votaciones.component.html',
  styleUrls: ['./votaciones.component.css']
})
export class VotacionesComponent implements OnInit {

  chart = [];
  totalVotos = [];
  cantVotos;

  ingresandoVoto = false;
  mostrarVotos = false;
  mostrarMotivo = false;
  motivo;

  idAnioActual;
  idMesActual;
  idTipoActual = 0;
  idVotoActual = 0;

  cargarSelect = 0;

  selectAnio;
  selectMes;
  selectTipo;
  selectVoto;

  datosVotarTemas = {
    id: '',
    fecha: '',
    titulo: '',
    descripcion: '',
    nombre: '',
    voto: null
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
    motivo: '',
    voto: ''
  };

  datosVoto = {
    tema: '',
    voto: null
  }

  temasActivos = [];
  temasHistorial = [];

  constructor(config: NgbModalConfig, private modalService: NgbModal, private _votaciones: VotacionesBryanmService, private _fechasService: PortalSociosService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
    this.cargarSelectHistorial();
  }

  abrirModalActivos(modal) {
    this.modalService.open(modal, { size: 'xl' });
    this.traerTemasActivos();
  }

  abrirModalVotos(modal) {
    this.modalService.open(modal, { size: 'lg' });
    this.votos();
  }

  abrirModalDetalle(modal, tema) {
    this.datosDetTemas.voto = '';
    this.traerVotoSocio(tema.id, 2);
    this.cargarDatosDetalle(tema);
    this.modalService.open(modal, { size: 'xl' });
  }

  abrirModalTema(modal, tema) {
    this.idVotoActual = 0;
    this.datosVotarTemas.voto = null;
    this.traerVotoSocio(tema.id, 1);
    this.cargarDatosTema(tema);
    this.traerTipoVotos();
    this.modalService.open(modal, { size: 'xl' });
  }

  cargarDatosTema(tema) {
    this.datosVotarTemas.id = tema.id;
    this.datosVotarTemas.fecha = tema.fecha_inicio;
    this.datosVotarTemas.titulo = tema.titulo;
    this.datosVotarTemas.descripcion = tema.descripcion;
    this.datosVotarTemas.nombre = tema.nombre;
  }

  traerVotoSocio(tema, tipo) {
    this._votaciones.traerVotoSocio(tema).subscribe(
      res => {
        if (res.estado == 'success') {
          if (tipo == 1) {
            this.datosVotarTemas.voto = res.voto.descripcion;
          } else {
            this.datosDetTemas.voto = res.voto.descripcion;
          }
        } else {
          if (tipo == 1) {
            this.datosVotarTemas.voto = null;
          } else {
            this.datosDetTemas.voto = '';
          }
        }
      },
      error => {
        console.log(error);
      }
    )
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
    this._votaciones.traerTiposSocios().subscribe(
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

  traerTipoVotos() {
    this._votaciones.traerTiposVotos().subscribe(
      res => {
        if (res.estado == 'success') {
          this.selectVoto = res.votos;
        }
      },
      error => {
        console.log(error);
      }
    )
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

  changeVoto(valorSelect) {
    this.idVotoActual = valorSelect.target.value;
  }

  cargarVoto() {
    this.datosVoto.tema = this.datosVotarTemas.id;
    this.datosVoto.voto = this.idVotoActual;
  }

  limpiarVoto() {
    this.datosVoto.tema = null;
    this.datosVoto.voto = null;
  }

  ingresarVoto() {
    this.cargarVoto();
    if (this.datosVoto.voto == 0) {
      alert('Debes seleccionar un voto.');
    } else {
      if (confirm('¿Estas seguro de ingresar tu voto?')) {
        this.ingresandoVoto = true;
        this._votaciones.ingresarVoto(this.datosVoto).subscribe(
          res => {
            if (res.estado == 'success') {
              this.ingresandoVoto = false;
              this.traerVotoSocio(this.datosVoto.tema, 1);
              this.idVotoActual = 0;
              this.limpiarVoto();
              alert(res.mensaje);
            } else {
              this.ingresandoVoto = false;
              alert(res.mensaje);
            }
          }, error => {
            this.ingresandoVoto = false;
            console.log(error);
          }
        )
      } else {
        this.ingresandoVoto = false;
      }
    }
  }

  traerHistorial() {
    this.temasHistorial = [];
    this._votaciones.traerHistorialSocios(this.idAnioActual, this.idMesActual, this.idTipoActual).subscribe(
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

  traerTemasActivos() {
    this.temasActivos = [];
    this._votaciones.traerActivosSocios().subscribe(
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
    this._votaciones.traerVotosSocios(tema).subscribe(
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
