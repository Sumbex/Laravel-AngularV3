import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { $ } from 'protractor';

@Component({
  selector: 'app-formulario-sindical',
  templateUrl: './formulario-sindical.component.html',
  styleUrls: ['./formulario-sindical.component.css']
})
export class FormularioSindicalComponent implements OnInit {

  constructor(private modalService: NgbModal) { }

  ngOnInit() {
  }

  openLg(content) {
    this.modalService.open(content, { size: 'lg' });
  }

}
