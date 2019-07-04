import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { cajaChicaSindical } from 'src/app/modelos/cajaChicaSindical.model';
import { CajaChicaService } from 'src/app/servicios/caja-chica.service';

@Component({
  selector: 'app-tabla-caja-chica',
  templateUrl: './tabla-caja-chica.component.html',
  styleUrls: ['./tabla-caja-chica.component.css']
})
export class TablaCajaChicaComponent implements OnInit {
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];
  cajaChica: cajaChicaSindical[] = [];

  constructor(private _cajaChicaService : CajaChicaService) { }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));

    //Cargar Caja chica
    this._cajaChicaService.getCajaChica('1').subscribe(
      response => {
        this.cajaChica = response;
        console.log(this.cajaChica);
      },
      error => {
        console.log(error);
      }
    );

  }

}
