import { Component, OnInit } from '@angular/core';
import { Meses } from 'src/app/modelos/meses.model';
import { Anios } from 'src/app/modelos/anios.model';
import { SindicalService } from 'src/app/servicios/sindical.service';
import { AniosService } from 'src/app/servicios/anios.service';

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

  anio;
  mes;

  valorAnio: Anios = {
    descripcion: ''
  }

  valorMes: Meses = {
    descripcion: ''
  }

  //actualizar caja chica

  suc_res1 = false;
  suc_res2 = false;
  //VARIABLES PARA LOS SELECT

  constructor(private _sindicalService: SindicalService, 
    private _fechasService: AniosService) { }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));

    this.cargar_select();
  }

  cargar_select(){

    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
     response => {
       this.anio = response;
       this.valorAnio.descripcion = this.idAnioActual.id; 
       this.suc_res1 = true;
       
       this.listo_para_listar(this.suc_res1, this.suc_res2);

     },
     error => {
       console.log(error);
     }
   )

   //Cargar id del Mes actual
   this._fechasService.getMesActual().subscribe(
     response => {
       this.mes = response;
       this.valorMes.descripcion = this.idMesActual.id;
       this.suc_res2 = true;
             
       this.listo_para_listar(this.suc_res1, this.suc_res2);
     },
     error => {
       console.log(error);
     }
   )

 }


 listo_para_listar(res1, res2){
  if (res1 == true && res2 == true) {
    //cargar tablitas funciones
    
  }
}

}
