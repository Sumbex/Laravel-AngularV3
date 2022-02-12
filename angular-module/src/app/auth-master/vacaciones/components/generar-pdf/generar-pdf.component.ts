import { Component, Input, OnInit } from '@angular/core';

@Component({
  selector: 'app-generar-pdf',
  templateUrl: './generar-pdf.component.html',
  styleUrls: ['./generar-pdf.component.css']
})
export class GenerarPdfComponent implements OnInit {
  s: any;
  dias_pendientes: number;

  @Input() set solicitud(data:any){
    if(data){
      this.s = data;
    }
  }
  e: any;
  @Input() set empleado(data:any){
    if(data){
      this.e = data;
    }
  }

  @Input() set dias_basicos_devengados(data:number){
    if(data){
      this.dias_pendientes = data;
    }
  }



  constructor() { }

  ngOnInit() {
  }

}
