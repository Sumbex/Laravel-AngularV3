import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SindicalService } from 'src/app/servicios/sindical.service';
import { AniosService } from 'src/app/servicios/anios.service';

@Component({
  selector: 'app-tabla-sindical',
  templateUrl: './tabla-sindical.component.html',
  styleUrls: ['./tabla-sindical.component.css']
})
export class TablaSindicalComponent implements OnInit {
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];
  idAnioActual;
  idMesActual;
  //TABLAS
  tablaSindical;
  fijos;
  variable;
  cajaChica;
  prestamo;
  camping;

  valorAnio: Anios = {
    descripcion: '1'
  }

  valorMes: Meses = {
    descripcion: '1'
  }


  constructor(config: NgbModalConfig, private modalService: NgbModal, private _sindicalService: SindicalService, private _fechasService: AniosService) {
    config.backdrop = 'static';
    config.keyboard = false;
    
  }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));

    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        this.idAnioActual = response;
        console.log(this.idAnioActual);
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
        console.log(this.idMesActual);
        this.valorMes.descripcion = this.idMesActual.id;
      },
      error => {
        console.log(error);
      }
    )
    
  }

  changeAnio(evento) {
    this.valorAnio.descripcion = evento.target.value;
    console.log("ID anio: " + this.valorAnio.descripcion);
  }

  changeMes(evento) {
    this.valorMes.descripcion = evento.target.value;
    console.log("ID mes: " + this.valorMes.descripcion);
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
