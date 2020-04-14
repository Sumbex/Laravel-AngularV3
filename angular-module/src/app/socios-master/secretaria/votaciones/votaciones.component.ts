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

  mostrarMotivo = false;
  motivo;

  idAnioActual;
  idMesActual;
  idTipoActual = 0;

  cargarSelect = 0;

  selectAnio;
  selectMes;
  selectTipo;

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
    this.cargarDatosDetalle(tema);
    this.modalService.open(modal, { size: 'xl' });
    /* this.votos(); */
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

  votos() {
    this.chart = new Chart("votosTema", {
      type: 'pie',
      data: {
        labels: ['Apruebo', 'Rechazo', 'Me Abstengo', 'Nulo'],
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
