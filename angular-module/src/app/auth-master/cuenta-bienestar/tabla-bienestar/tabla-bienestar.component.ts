import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-tabla-bienestar',
  templateUrl: './tabla-bienestar.component.html',
  styleUrls: ['./tabla-bienestar.component.css']
})
export class TablaBienestarComponent implements OnInit {

  constructor(config: NgbModalConfig, private modalService: NgbModal) {
    config.backdrop = 'static';
    config.keyboard = false;
    
  }

  ngOnInit() {
  }

  openTablaBienestar(TablaBienestar) {
    this.modalService.open(TablaBienestar, { size: 'lg' });
  }

}
