import { Component, OnInit, ViewChild } from '@angular/core';
import { cajaChicaSindical } from 'src/app/modelos/cajaChicaSindical.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { Definicion } from 'src/app/modelos/definicion.model';
import { CajaChicaService } from 'src/app/servicios/caja-chica.service';
import { TablaCajaChicaComponent } from './tabla-caja-chica/tabla-caja-chica.component';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';

@Component({
  selector: 'app-modal-caja-chica',
  templateUrl: './modal-caja-chica.component.html',
  styleUrls: ['./modal-caja-chica.component.css']
})
export class ModalCajaChicaComponent implements OnInit {
  @ViewChild(TablaCajaChicaComponent, {static: false}) private tablaComponent : TablaCajaChicaComponent;

  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];
  cajaChica: cajaChicaSindical[] = [];

  selectDefinicion: Definicion[] = [];

  datosCajaChica: cajaChicaSindical ={
    numero_documento:0,
    archivo_documento:'',
    fecha:'',
    descripcion:'',
    definicion:'1',
    monto_ingreso:0,
    monto_egreso:0
  }
  
  constructor(config: NgbModalConfig, private modalService: NgbModal, private _cajaChicaService: CajaChicaService) {
    config.backdrop = 'static';
    config.keyboard = false;
    
  }

  ngOnInit() {

    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));

    //Cargar Caja chica
    this.refrescarCajaChica();

    //Cargar definiciones
    this.selectDefinicion = JSON.parse(localStorage.getItem('definicion'));
  }

  openCajaChica(CajaChica) {
    this.modalService.open(CajaChica, { size: 'lg' });
  }

  tipoOperacionDefinicion(evento){
    this.datosCajaChica.definicion = evento.target.value;
    console.log(this.datosCajaChica.definicion);
  }

  onSubmit({value, valid}: {value: cajaChicaSindical, valid: boolean}) {
    if(!valid){
      console.log("Ingreso no valido revisar campos");
    }else{
      /*this._cajaChicaService.ingresarValor(this.datosCajaChica).subscribe(
        response => {
          console.log(response);
        },
        error => {
          console.log(<any>error);
        }
      );*/
      this.tablaComponent.refrescarCajaChica();
    }
  }

  refrescarCajaChica(){
    //Cargar Caja chica
    console.log("refrescando caja chica");
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
