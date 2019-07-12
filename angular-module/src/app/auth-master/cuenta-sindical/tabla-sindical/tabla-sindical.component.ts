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
  loading = false;
  //TABLAS
  tablaSindical;
  fijos;
  variable;
  cajaChica;
  prestamo;
  camping;
  resultado;

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
      },
      error => {
        console.log(error);
      }
    )

  }

  changeAnio(evento) {
    this.valorAnio.descripcion = evento.target.value;
    console.log("ID anio: " + this.valorAnio.descripcion);
    this.refrescarSindical();
  }

  changeMes(evento) {
    this.valorMes.descripcion = evento.target.value;
    console.log("ID mes: " + this.valorMes.descripcion);
    this.refrescarSindical();
  }

  openTablaSindical(TablaSindical) {
    this.modalService.open(TablaSindical, { size: 'lg' });
    this.refrescarSindical();
  }

  refrescarSindical() {
    this.tablaSindical = [];
          this.fijos = [];
          this.variable = [];
          this.cajaChica = [];
          this.prestamo = [];
          this.camping = [];
          this.resultado = [];
    this._sindicalService.getTablaSindical(this.valorAnio.descripcion, this.valorMes.descripcion).subscribe(
      response => {
        if (response == null) {
          this.tablaSindical = [];
          this.fijos = [];
          this.variable = [];
          this.cajaChica = [];
          this.prestamo = [];
          this.camping = [];
          this.resultado = [];
        }else{
          this.tablaSindical = response;
          this.fijos = this.tablaSindical.fijo;
          this.variable = this.tablaSindical.variable;
          this.cajaChica = this.tablaSindical.caja_chica;
          this.prestamo = this.tablaSindical.prestamo;
          this.camping = this.tablaSindical.camping;
          this.resultado = this.tablaSindical.resultado;
          console.log(this.tablaSindical);
        }
        this.loading = false;
      },
      error => {
        console.log(error);
      }
    );
    this.loading = true;
  }

}
