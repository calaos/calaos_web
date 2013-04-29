/* 
   gcc -o graph.cgi graph.c `pkg-config eet eina --cflags --libs` -ggdb -g3 -O0
   QUERY_STRING="probe=input_10&precision=h&start=1367100000&stop=1367100000"  ./graph.cgi datalogger.eet
*/

#include <stdio.h>

#include <Eina.h>
#include <Eet.h>

typedef struct _Calaos_Graph_Args Calaos_Graph_Args;
typedef struct _Calaos_DataLogger_Values Calaos_DataLogger_Values;
typedef struct _Calaos_DataLogger_List Calaos_DataLogger_List;
typedef enum _Calaos_Graph_Precision Calaos_Graph_Precision;

#define CHECK_RANGE(val) (args->start->tm_##val != args->stop->tm_##val && args->start->tm_##val < args->stop->tm_##val)

enum _Calaos_Graph_Precision
{
  CALAOS_GRAPH_PRECISION_YEAR,
  CALAOS_GRAPH_PRECISION_MONTH,
  CALAOS_GRAPH_PRECISION_DAY,
  CALAOS_GRAPH_PRECISION_HOUR,
  CALAOS_GRAPH_PRECISION_MINUTE,
  CALAOS_GRAPH_PRECISION_UNKNOWN,
};

static const struct {
    const char *arg;
    Calaos_Graph_Precision val ;
} precision_values[] = {
    { "year",     CALAOS_GRAPH_PRECISION_YEAR},
    { "month",    CALAOS_GRAPH_PRECISION_MONTH},
    { "day",      CALAOS_GRAPH_PRECISION_DAY},
    { "hour",     CALAOS_GRAPH_PRECISION_HOUR},
    { "minute",   CALAOS_GRAPH_PRECISION_MINUTE},
    { NULL,       CALAOS_GRAPH_PRECISION_UNKNOWN}
};


struct _Calaos_Graph_Args
{
  const char *probe;
  Calaos_Graph_Precision precision;
  time_t epoch_start;
  time_t epoch_stop;
  struct tm *start;
  struct tm *stop;
};

struct _Calaos_DataLogger_Values
{
  time_t timestamp;
  double value;

};

struct _Calaos_DataLogger_List
{
  Eina_List *list;
};

static Eet_Data_Descriptor *calaos_datalogger_values_edd = NULL;
static Eet_Data_Descriptor *calaos_datalogger_list_edd = NULL;
static Calaos_Graph_Args *args = NULL;
static const char *filename;
static void
_init_eet_descriptors(void)
{
  Eet_Data_Descriptor_Class eddc;
  Eet_Data_Descriptor *edd;

  /* Data Descriptor for time/value */
  EET_EINA_FILE_DATA_DESCRIPTOR_CLASS_SET(&eddc, Calaos_DataLogger_Values);
  edd = eet_data_descriptor_stream_new(&eddc);

  EET_DATA_DESCRIPTOR_ADD_BASIC(edd, Calaos_DataLogger_Values, "timestamp", timestamp, EET_T_INT);
  EET_DATA_DESCRIPTOR_ADD_BASIC(edd, Calaos_DataLogger_Values, "value", value, EET_T_DOUBLE);

  calaos_datalogger_values_edd = edd;

  /* Data Descriptor for list of values */
  EET_EINA_FILE_DATA_DESCRIPTOR_CLASS_SET(&eddc, Calaos_DataLogger_List);
  edd = eet_data_descriptor_stream_new(&eddc);

  EET_DATA_DESCRIPTOR_ADD_LIST(edd, Calaos_DataLogger_List, "list", list, calaos_datalogger_values_edd);

  calaos_datalogger_list_edd = edd;
}

static void
_print_values_mins(Calaos_Graph_Args *args)
{
  char section[256];
  Calaos_DataLogger_List *list;
  Eet_File *ef;
  Calaos_DataLogger_Values *value;
  Eina_List *l;
  int list_len = 0;
  Eina_List *filtered;

  ef = eet_open(filename, EET_FILE_MODE_READ_WRITE);

  snprintf(section, sizeof(section), "calaos/sonde/%s/%d/%d/%d/%d/values", args->probe, 
	   args->start->tm_year + 1900, args->start->tm_mon + 1, args->start->tm_mday, args->start->tm_hour);

  list =  eet_data_read(ef, calaos_datalogger_list_edd, section);
  if (list)
    {
      int i = 0;

      EINA_LIST_FOREACH(list->list, l, value)
	{
	  if (value->value > 100.0)
	    list->list = eina_list_remove(list->list, value);
	}

      list_len = eina_list_count(list->list);

      EINA_LIST_FREE(list->list, value)
	{
	  printf("[%ld, %3.3f]", value->timestamp * 1000, value->value);
	  i++;
	  if (i != list_len)
	    printf(",");		  
	}
    }

  eet_close(ef);
}

static void
_print_values_hours(Calaos_Graph_Args *args)
{
  char section[256];
  Calaos_DataLogger_List *list;
  Eet_File *ef;
  Calaos_DataLogger_Values *value;
  Eina_List *l;
  int list_len = 0;
  Eina_List *filtered;
  int year, mon, mday, hour = 0;

  ef = eet_open(filename, EET_FILE_MODE_READ_WRITE);
  
  for (hour = args->start->tm_hour; hour <=  args->stop->tm_hour ; hour++)
   
    {
      snprintf(section, sizeof(section), "calaos/sonde/%s/%d/%d/%d/%d/values", args->probe, 
	       year +  1900, mon + 1, mday, hour);

      list =  eet_data_read(ef, calaos_datalogger_list_edd, section);
      if (list)
	{
	  int i = 0;

	  EINA_LIST_FOREACH(list->list, l, value)
	    {
	      if (value->value > 100.0)
		list->list = eina_list_remove(list->list, value);
	    }

	  list_len = eina_list_count(list->list);

	  EINA_LIST_FREE(list->list, value)
	    {
	      printf("[%ld, %3.3f]", value->timestamp * 1000, value->value);
	      i++;
	      if ((i == list_len) && (hour == (args->stop->tm_hour)))
		printf("");
	      else
		printf(",");		  
	    }

	}
    }

  eet_close(ef);
}

static void
_print_values(Calaos_Graph_Args *args)
{

  printf("Content-Type: application/json\r\n\r\n");
  printf("[{\"data\":[");
  /* if (CHECK_RANGE(year)) */
  /*   printf("print years\n"); */
  /*   //_print_values_years(args); */
  /* else if (CHECK_RANGE(mon)) */
  /*   printf("Year equal\n"); */
  /*   //_print_values_months(args); */
  /* else if (CHECK_RANGE(mday)) */
  /*   printf("Mon equal\n"); */
  /*   //_print_values_mdays(args); */
  /* else  */if ((args->start->tm_hour != args->stop->tm_hour) && (args->start->tm_hour < args->stop->tm_hour))
    _print_values_hours(args);
  else
    _print_values_mins(args);

  printf("]}]\n");
}

static Calaos_Graph_Precision
_str2precision(const char *str)
{
  int i;
  for (i = 0; precision_values[i].arg; i++)
    if (!strcmp(str, precision_values[i].arg))
      return precision_values[i].val;
  return 0;
}

Eina_Bool
_parse(Calaos_Graph_Args *args)
{
  char * query = getenv("QUERY_STRING");
  char **elements;
  char **tokens;
  int i,j;
  char buf[20];
  Eina_Bool ret = EINA_TRUE;
  struct tm * tmp;

  if (!args)
    return EINA_FALSE;
  
  elements = eina_str_split(query, "&", 0);
  if (!elements)
    return EINA_FALSE;

  for (i = 0; elements[i]; i++)
    {
      tokens = eina_str_split(elements[i], "=", 0);
      if (!tokens)
	{
	  ret = EINA_FALSE;
	  goto error;
	}

      for (j = 0; tokens[j]; j++)
	{
	  if (j == 1)
	    {
	      if (!strcmp(tokens[j-1], "probe"))
		args->probe = eina_stringshare_add(tokens[j]);
	      else if (!strcmp(tokens[j-1], "precision"))
		args->precision = _str2precision(tokens[j]);
	      else if (!strcmp(tokens[j-1], "start"))
		args->epoch_start = atoi(tokens[j]);
	      else if (!strcmp(tokens[j-1], "stop"))
		args->epoch_stop = atoi(tokens[j]);
	    }
	}

      free(tokens[0]);
      free(tokens);

    }
 

  tmp = localtime(&(args->epoch_start));
  args->start = calloc(1, sizeof(struct tm));
  memcpy(args->start, tmp, sizeof(struct tm));

  tmp = localtime(&(args->epoch_stop));
  args->stop = calloc(1, sizeof(struct tm));
  memcpy(args->stop, tmp, sizeof(struct tm));

  /* printf("Probe : %s\n", args->probe); */
  /* printf("Precision : %d\n", args->precision); */
  /* strftime(buf, sizeof(buf), "%Y-%m-%d %H:%M:%S", args->start); */
  /* printf("Start : %s\n", buf); */
  /* strftime(buf, sizeof(buf), "%Y-%m-%d %H:%M:%S", args->stop); */
  /* printf("Stop : %s\n", buf); */


 error:
   free(elements[0]);
   free(elements);
   return ret;
}

int main(int argc, char** argv)
{
  char *query;
  char *phrase;

  eina_init();
  eet_init();

  if (argc > 1)
    filename = eina_stringshare_add(argv[1]);
  else
    filename = eina_stringshare_add("/etc/calaos/datalogger.eet");

  args = calloc(1, sizeof(Calaos_Graph_Args));


  if (!_parse(args))
    goto error;

  _init_eet_descriptors();
  _print_values(args);

 error:
  eet_shutdown();
  eina_shutdown();
}
