import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { Actividades } from 'src/app/modelos/actividadesDirecctorio.model';
import { ActividadesDirectorioService } from 'src/app/servicios/actividades-directorio.service';
import { PortalSociosService } from 'src/app/servicios/portal-socios.service';

@Component({
  selector: 'app-actividades-directorio-socios',
  templateUrl: './actividades-directorio-socios.component.html',
  styleUrls: ['./actividades-directorio-socios.component.css']
})
export class ActividadesDirectorioSociosComponent implements OnInit {

  cargarSelect: number = 0;

  anioId;
  selectAnios: Anios[];

  mostrar: boolean = false;

  filtroId = 0;
  selectFiltro = [];

  mesId;
  selectMeses: Meses[];

  cargandoTabla: boolean = false;

  actividad: Actividades = new Actividades;
  actividades: Actividades[];
  actividadesFiltro: Actividades[];

  constructor(public _actividad: ActividadesDirectorioService, private _fechasService: PortalSociosService) {
  }

  ngOnInit() {
    this.selectAnios = JSON.parse(localStorage.getItem('anios'));
    this.selectMeses = JSON.parse(localStorage.getItem('meses'));
    this.cargarSelectActividades();
  }

  mostarTexto() {
    this.mostrar = !this.mostrar;
  }

  changeAnio(valorSelect) {
    this.anioId = valorSelect.target.value;
    this.traerActividades();
  }

  changeMes(valorSelect) {
    this.mesId = valorSelect.target.value;
    this.traerActividades();
  }

  changeFiltro(valorSelect) {
    this.filtroId = valorSelect.target.value;
    this.filtrarAct(this.filtroId);
  }

  cargarSelectActividades() {
    this.cargarSelect = 0;
    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.anioId = response.id;
        this.cargarSelect++;
        if (this.cargarSelect == 3) {
          this.traerActividades();
        }
      },
      error => {
        console.log(error);
      }
    )
    //Cargar id del Mes actual
    this._fechasService.getMesActual().subscribe(
      response => {
        this.mesId = response.id;
        this.cargarSelect++;
        if (this.cargarSelect == 3) {
          this.traerActividades();
        }
      },
      error => {
        console.log(error);
      }
    )

    //Cargar id del Filtro actual
    this._actividad.traerTiposActSocios().subscribe(
      response => {
        this.selectFiltro = response.tipos;
        this.filtroId = 0;
        this.cargarSelect++;
        if (this.cargarSelect == 3) {
          this.traerActividades();
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  filtrarAct(filtroId) {
    if (filtroId == 0) {
      this.actividadesFiltro = this.actividades;
    } else {
      this.actividadesFiltro = this.actividades.filter(filtro => filtro.estado_actividad_id == filtroId);
    }
  }

  traerActividades() {
    this.actividadesFiltro = [];
    this.actividades = [];
    this.filtroId = 0;
    this.cargandoTabla = true;
    this._actividad.traerActividadesSocios(this.anioId, this.mesId).subscribe(
      res => {
        if (res.estado == 'success') {
          this.cargandoTabla = false;
          this.actividades = res.actividades;
          this.actividadesFiltro = res.actividades;
        } else {
          this.cargandoTabla = false;
          alert(res.mensaje);
        }
      }, error => {
        this.cargandoTabla = false;
        console.log(error);
      }
    )
  }

}
