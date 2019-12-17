import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-directorio',
  templateUrl: './directorio.component.html',
  styleUrls: ['./directorio.component.css']
})
export class DirectorioComponent implements OnInit {

  fecha = '';
  tipoReunion = '';
  titulo = '';
  presidente = false;
  tesorero = false;
  secretario = false;
  subGerente = '';
  gerente = '';
  temas = '';

  //variable para asociar al modal
  modalVariable;

  constructor(config: NgbModalConfig, private modalService: NgbModal) {
    config.backdrop = 'static';
    config.keyboard = false;
  }
  ngOnInit() {
  }

  abrirModal(modalDirectiva){
    this.modalVariable = this.modalService.open(modalDirectiva, {size: 'xl'});
  }

  ingresarDirectorio(directorio)
  {
    console.log(directorio.value);
  }

}
