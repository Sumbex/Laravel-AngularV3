import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-tabla-inicio-mes',
  templateUrl: './tabla-inicio-mes.component.html',
  styleUrls: ['./tabla-inicio-mes.component.css']
})
export class TablaInicioMesComponent implements OnInit {
  selectAnio: Anios[] = [];

  constructor(private _aniosService: AniosService) { }

  ngOnInit() {
    //Cargar Años
    this._aniosService.getAnios().subscribe((res : any[]) => {
    this.selectAnio = res;
    });
  }

}
