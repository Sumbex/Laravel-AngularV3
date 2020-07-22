import { Component, OnInit } from '@angular/core';
import { AcuerdoAsambleaService } from 'src/app/servicios/acuerdo-asamblea.service';
import { Acuerdo } from 'src/app/modelos/acuerdo.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-acuerdos-socio',
  templateUrl: './acuerdos-socio.component.html',
  styleUrls: ['./acuerdos-socio.component.css']
})
export class AcuerdosSocioComponent implements OnInit {

  //Tabla de Acuerdos
  acuerdos: Acuerdo[] = [];

  acuerdo: Acuerdo = new Acuerdo();

  modal;
  
  constructor(
    public _acuerdosService: AcuerdoAsambleaService,
    config: NgbModalConfig, 
    private modalService: NgbModal
  ) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    this.getAcuerdos();
  }

  abrirModalTest(modalMenutest, acuerdoId: string){
    this.acuerdo = new Acuerdo();
    this.modal = this.modalService.open(modalMenutest, {size: 'xl'});
    this._acuerdosService.getAcuerdoPorId(acuerdoId).subscribe(response => {
      this.acuerdo = response.acuerdo;
    }, error => {
      console.log(error);
    });
  }

  getAcuerdos(){
    this._acuerdosService.getAcuerdosAsamblea().subscribe(response => {
      this.acuerdos = response.acuerdos;
    }, error=> {
      console.log(error);
    });
  }

}
