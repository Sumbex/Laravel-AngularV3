import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';


@Component({
  selector: 'app-modal-camping',
  templateUrl: './modal-camping.component.html',
  styleUrls: ['./modal-camping.component.css']
})
export class ModalCampingComponent implements OnInit {

  constructor(config: NgbModalConfig, private modalService: NgbModal) {
    config.backdrop = 'static';
    config.keyboard = false;

  }
  ngOnInit() {
  }

  openCamping(Camping) {
    this.modalService.open(Camping, { size: 'lg' });
  }

}
