import { Component, OnInit } from '@angular/core';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { cajaChicaSindical } from 'src/app/modelos/cajaChicaSindical.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { AniosService } from 'src/app/servicios/anios.service';
import { TipoCuentasService } from 'src/app/servicios/tipo-cuentas.service';
import { Definicion } from 'src/app/modelos/definicion.model';
import { CajaChicaService } from 'src/app/servicios/caja-chica.service';

@Component({
  selector: 'app-modal-caja-chica',
  templateUrl: './modal-caja-chica.component.html',
  styleUrls: ['./modal-caja-chica.component.css']
})
export class ModalCajaChicaComponent implements OnInit {
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
  
  constructor(config: NgbModalConfig, private modalService: NgbModal, private _tiposService: TipoCuentasService, private _cajaChicaService: CajaChicaService) {
    config.backdrop = 'static';
    config.keyboard = false;
    
  }

  ngOnInit() {
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

  onSubmit({value, valid}: {value: cajaChicaSindical, valid: boolean}) {
    if(!valid){
      console.log("Ingreso no valido revisar campos");
    }else{
      this._cajaChicaService.ingresarValor(this.datosCajaChica).subscribe(
        response => {
          console.log(response);
        },
        error => {
          console.log(<any>error);
        }
      );
    }
  }

}
