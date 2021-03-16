import { CumpleanioService } from './../../servicios/cumpleanio.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-index',
  templateUrl: './index.component.html',
  styleUrls: ['./index.component.css']
})
export class IndexComponent implements OnInit {

  count_cumpleanios=0;
  constructor(private cumpleanio:CumpleanioService) { }

  ngOnInit() {
    this.traer_cantidad_cumpleanios();
  }

  traer_cantidad_cumpleanios(){
    this.cumpleanio.cantidad_cumpleanios().subscribe((res)=>{
      this.count_cumpleanios = res;
    });
  }

}
