//matopeli.java

import java.awt.event.*;
import java.awt.*;
import java.applet.Applet;

public class matopeli extends Applet implements Runnable, KeyListener
{
    int suunta=0, vanhasuunta=0;
    int x[] = new int [300];
    int y[] = new int [300];
    int uusx=10,uusy=7;
    int pituus=3;
    int kasvata=0;
    int namix, namiy;
    int roll=0;
    int alku=1;
    Graphics db;
    Image dblbuf;
    Color green1=new Color(0,192,0);
    Color green2=new Color(0,128,0);
    Color yellow1=new Color(255,255,0);
    Color yellow2=new Color(192,192,0);
    Font otsikkofontti;
    Font perusfontti;

    Thread runner=null;

    public void init ()
    {
        setBackground(Color.black);
        addKeyListener(this);
        int n;
        for (n=0;n<300;n++) //"alustetaan" mato
        {
            x[n]=10;
            y[n]=7;
        }

        otsikkofontti=new Font("SansSerif", Font.PLAIN, 24);
        perusfontti=new Font("SansSerif", Font.PLAIN, 11);

        dblbuf=createImage(320,240); //kaksoispuskurointijuttuja
        db=dblbuf.getGraphics();

        int pois=0;

        while (pois!=1) //pistetään herkkupala kentälle
        {
            namix=(int) (Math.random()*19);
            namiy=(int) (Math.random()*13)+1;

            pois=1;
            for (n=pituus;n>=0;n--)
            {
                if (namix==10 && namiy==7)
                    pois=0;
            }
        }
    }

    public void stop()
    {
        if (runner != null) runner.stop();
        runner=null;
    }

    public void run() {
        while (true)
        {
            repaint();

            try
            {
                runner.sleep(150); //hidastus
            }
            catch (InterruptedException e) {}

        }
    }

    public int tarkistus(int c)
    {
        int n,q,gameover=0;
        for (n=1;n<pituus;n++)
        {
            q=c-n;
            if (q<0)
                q+=300;

            if (x[q]==x[c] && y[q]==y[c]) //tarkistetaan törmääkö mato itseensä
                gameover=1;
        }
        if (x[c]<0 || x[c]>19 || y[c]<1 || y[c]>14) //tarkistetaan törmääkö reunoihin
            gameover=1;

        if (gameover==1) //jos törmäsi, mennään tänne
        {
            stop();
            uusx=10;
            uusy=7;
            for (n=0;n<300;n++) //"alustetaan" mato
            {
                x[n]=10;
                y[n]=7;
            }
        }



        return gameover;

    }


    public void nami(int c) //herkkutarkistus
    {
        int n,q,pois=0;
        if (namix==x[c] && namiy==y[c]) //jos mato syö herkkupalan
        {
            kasvata+=3;

            while (pois!=1) //luodaan uusi herkkupala
            {
                namix=(int) (Math.random()*19);
                namiy=(int) (Math.random()*13)+1;

                pois=1;
                for (n=0;n<pituus;n++)
                {
                    q=c-n;
                    if (q<0)
                        q+=299;
                    if (namix==x[q] && namiy==y[q])
                        pois=0;
                }
            }
        }
    }

    public boolean imageUpdate (Image i, int info, int xx, int yy, int width, int height) { return true;}




    public void paint(Graphics g) //piirretään kaikki roska näytölle
    {
        if (alku==1) //piirretään alkuruutu
        {
            FontMetrics fm;
            int leveys;

            db.setFont(otsikkofontti); //asetetaan käytetty fontti
            db.setColor(Color.white); //fontin väri
            fm = db.getFontMetrics(); //haetaan fontin tiedot
            leveys = fm.stringWidth("Java-Matopeli"); //katsotaan kuinka pitkä merkkijono pikseleinä
            db.drawString("Java-Matopeli",160-leveys/2,40); //piirretään teksti keskelle

            db.setFont(perusfontti);
            fm = db.getFontMetrics();
            leveys = fm.stringWidth("Näppäimet:");
            db.drawString("Näppäimet:",160-leveys/2,100);

            leveys = fm.stringWidth("Space käynnistää pelin");
            db.drawString("Space käynnistää pelin",160-leveys/2,120);

            leveys = fm.stringWidth("Nuolet ohjaavat matoa");
            db.drawString("Nuolet ohjaavat matoa",160-leveys/2,140);

            leveys = fm.stringWidth("By Jogge");
            db.drawString("By Jogge",315-leveys,233);

            db.drawString("www.saippuarasia.urli.net",5,233);

            g.drawImage(dblbuf,0,0,this); //kuva kaksoispuskuriin

            alku=0;
        }
    }


    public void update (Graphics g) //lisää näytölle piirtämistä
    {
        if (suunta==0) //madon liikutusta
            uusy-=1;
        if (suunta==1)
            uusy+=1;
        if (suunta==2)
            uusx-=1;
        if (suunta==3)
            uusx+=1;

        vanhasuunta=suunta;


        int c=0;

        if (runner!=null) //jos peli on käynnissä
        {
            c = roll + pituus;
            if (c > 299)
                c -= 300;
            x[c]=uusx;
            y[c]=uusy;

            if (tarkistus(c)==1) //game over
            {

                FontMetrics fm;
                int leveys;

                db.setFont(otsikkofontti);
                db.setColor(Color.white);
                fm = db.getFontMetrics();
                leveys = fm.stringWidth("Game over!");
                db.drawString("Game over!",160-leveys/2,132);
                g.drawImage(dblbuf,0,0,this);
                db.setFont(perusfontti);

            }

            nami(c); //herkkutarkistus
        }

        if (runner!=null) //jos peli käynnissä
        {

            db.setColor(Color.black); //piirtoväri
            db.fillRect(0,0,320,14); //piirretään neliö
            db.setColor(Color.white);
            db.drawString("Pituus: "+pituus,2,12); //päivitetään madon pituus
            db.drawLine(0,15,320,15);

            db.setColor(yellow1);
            db.fillRect(namix*16,namiy*16,15,15);
            db.setColor(yellow2);
            db.drawRect(namix*16,namiy*16,15,15);



            db.setColor(Color.black);
            db.fillRect(x[roll]*16,y[roll]*16,16,16);

            db.setColor(green1);
            db.fillRect(x[c]*16,y[c]*16,15,15);
            db.setColor(green2);
            db.drawRect(x[c]*16,y[c]*16,15,15);

            g.drawImage(dblbuf,0,0,this); //kaksoispuskuri näytölle


            if (kasvata==0)
                roll++;
            else
            {
                kasvata--; //madon kasvatus
                pituus++;
            }
            if (roll > 299)
                roll -= 300;
        }
    }

    public void keyPressed (KeyEvent e) //luetaan näppistä
    {
        String nappi=e.getKeyText(e.getKeyCode());

        if (nappi.equals("Up") && vanhasuunta != 1)
            suunta=0;
        if (nappi.equals("Down") && vanhasuunta != 0)
            suunta=1;
        if (nappi.equals("Left") && vanhasuunta != 3)
            suunta=2;
        if (nappi.equals("Right") && vanhasuunta != 2)
            suunta=3;

        if (nappi.equals("Space") && runner==null) //aloitetaan (uusi) peli
        {
            runner = new Thread(this);
            runner.start();
            pituus=3;
            db.setColor(Color.black);
            db.fillRect(0,0,320,240);
            suunta=0;
            vanhasuunta=0;
        }


    }

    public void keyTyped (KeyEvent e) {}
    public void keyReleased (KeyEvent e) {}

}