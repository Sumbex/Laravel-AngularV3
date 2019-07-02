import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { cajaChicaSindical } from 'src/app/modelos/cajaChicaSindical.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AniosService } from 'src/app/servicios/anios.service';
import { TipoCuentasService } from 'src/app/servicios/tipo-cuentas.service';
import { Definicion } from 'src/app/modelos/definicion.model';

@Component({
  selector: 'app-modal-caja-chica',
  templateUrl: './modal-caja-chica.component.html',
  styleUrls: ['./modal-caja-chica.component.css']
})
export class ModalCajaChicaComponent implements OnInit {

  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];
  selectDefinicion: Definicion[] = [];

  datoCajaChica: cajaChicaSindical[];
  datosCajaChica: cajaChicaSindical ={
    numero_documento:0,
    archivo_documento:'',
    descripcion:'',
    definicion:'',
    monto_ingreso:0,
    monto_egreso:0
  }
  
  constructor(config: NgbModalConfig, private modalService: NgbModal, private _aniosService: AniosService, private _tiposService: TipoCuentasService) {
    config.backdrop = 'static';
    config.keyboard = false;
    
  }

  ngOnInit() {
    //Cargar Años
    this._aniosService.getAnios().subscribe((res : any[]) => {
      this.selectAnio = res;
    });
    //Cargar Meses
    this._aniosService.getMeses().subscribe((res : any[]) => {
      this.selectMes = res;
    });
    //Cargar Definiciones
    this._tiposService.getDefinicion().subscribe((res : any[]) => {
      this.selectDefinicion = res;
      console.log(res);
    });
  }

  openCajaChica(CajaChica) {
    this.modalService.open(CajaChica, { size: 'lg' });
  }

  tipoOperacionDefinicion(evento){
    this.datosCajaChica.definicion = evento.target.value;
    console.log(this.datosCajaChica.definicion);
  }

}
