import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SindicalService } from 'src/app/servicios/sindical.service';

@Component({
  selector: 'app-tabla-sindical',
  templateUrl: './tabla-sindical.component.html',
  styleUrls: ['./tabla-sindical.component.css']
})
export class TablaSindicalComponent implements OnInit {
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];
  //TABLAS
  tablaSindical;
  fijos;
  variable;
  cajaChica;
  prestamo;
  camping;


  constructor(config: NgbModalConfig, private modalService: NgbModal, private _sindicalService: SindicalService) {
    config.backdrop = 'static';
    config.keyboard = false;
    
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
  }

  openTablaSindical(TablaSindical) {
    this.modalService.open(TablaSindical, { size: 'lg' });
    this.refrescarSindical();
  }

  refrescarSindical(){
    this._sindicalService.getTablaSindical('1','1').subscribe(
      response => {
        this.tablaSindical = response;
        this.fijos = this.tablaSindical.fijo;
        this.variable = this.tablaSindical.variable;
        this.cajaChica = this.tablaSindical.caja_chica;
        this.prestamo = this.tablaSindical.prestamo;
        this.camping = this.tablaSindical.camping;
        console.log(this.tablaSindical);
      },
      error => {
        console.log(error);
      }
    );
  }

}
