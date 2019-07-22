import { Component, OnInit } from '@angular/core';
import { Meses } from 'src/app/modelos/meses.model';
import { Anios } from 'src/app/modelos/anios.model';
import { SindicalService } from 'src/app/servicios/sindical.service';
import { AniosService } from 'src/app/servicios/anios.service';
import { HttpClient } from '@angular/common/http';
import { Prestamos } from 'src/app/modelos/prestamos.model';

@Component({
  selector: 'app-tabla-prestamos-socios',
  templateUrl: './tabla-prestamos-socios.component.html',
  styleUrls: ['./tabla-prestamos-socios.component.css']
})
export class TablaPrestamosSociosComponent implements OnInit {

  //VARIABLES PARA LOS SELECT
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];
  idAnioActual;
  idMesActual;

  valorAnio: Anios = {
    descripcion: ''
  }

  valorMes: Meses = {
    descripcion: ''
  }
  //VARIABLES PARA LOS SELECT

  datosPrestamosSocios:Prestamos = {
    fecha: '',
    selectId: '',
    socioId: '',
    numeroDocumento: '',
    archivoDocumento: '',
    montoPrestamo: '',
    checkAbono: null,
    cuotas: '',
    montoDia: '',
    montoTri: '',
    montoCon: '',
    checkDia: null,
    checkTri: null,
    checkCon: null
  };

  valoresCajaChica;

  constructor(private _sindicalService: SindicalService, 
    private _fechasService: AniosService) { }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));

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
        this.refrescarTablaPrestamosClientes();
      },
      error => {
        console.log(error);
      }
    )
  }

  refrescarTablaPrestamosClientes(){
    this._sindicalService.getPrestramosSocios('1','7').subscribe(
      response => {
        this.valoresCajaChica = response;
        console.log(this.valoresCajaChica);
        console.log(this.valoresCajaChica.fecha);
      },
      error => {
        console.log(error);
      }
    )
  }
}
