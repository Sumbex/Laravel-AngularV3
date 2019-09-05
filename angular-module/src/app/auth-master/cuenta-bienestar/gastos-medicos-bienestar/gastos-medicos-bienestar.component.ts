import { Component, OnInit, Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-gastos-medicos-bienestar',
  templateUrl: './gastos-medicos-bienestar.component.html',
  styleUrls: ['./gastos-medicos-bienestar.component.css']
})
export class GastosMedicosBienestarComponent implements OnInit {

  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';

  constructor() {

  }

  ngOnInit() {
  }

}
