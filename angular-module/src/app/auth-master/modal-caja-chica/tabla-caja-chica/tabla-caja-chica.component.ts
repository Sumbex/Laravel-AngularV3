import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-tabla-caja-chica',
  templateUrl: './tabla-caja-chica.component.html',
  styleUrls: ['./tabla-caja-chica.component.css']
})
export class TablaCajaChicaComponent implements OnInit {
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];

  constructor(private _aniosService: AniosService) { }

  ngOnInit() {
    //Cargar Años
    this._aniosService.getAnios().subscribe((res : any[]) => {
      this.selectAnio = res;
    });
    //Cargar Meses
    this._aniosService.getMeses().subscribe((res : any[]) => {
      this.selectMes = res;
    });
  }

}
